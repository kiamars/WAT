<?php

    /**
     * Created by PhpStorm.
     * User: Mirzaee
     * Date: 10/30/2016
     * Time: 1:31 PM
     */

    require_once "BaseDirectory.php";
    require_once $BASEDIROFPROJECT . "/TestDataGenerationAlgorithm/ExecutedBranchList.inc";
    require_once $BASEDIROFPROJECT . "/TestDataGenerationAlgorithm/Components/FitnessFunction/BD.inc";
    require_once $BASEDIROFPROJECT . "/TestDataGenerationAlgorithm/Components/TestSequenceAndTestData/ListOfAbstractUrls.php";
    require_once $BASEDIROFPROJECT . "/TestDataGenerationAlgorithm/Components/Result/TDGResultForpageUnderTest.php";
    require_once $BASEDIROFPROJECT . "/TestDataGenerationAlgorithm/Components/Solution/Solution.php";
    require_once $BASEDIROFPROJECT . "/TestDataGenerationAlgorithm/Components/Inputs/InputsInclude.php";

    abstract class PageTDG
    {
        public $ExecutedBranchList = array();
        public $Result = null;
        public $CoverageTable = null;
        public $CurrentSolution = null;
        public $CurrentInterface = null;

        public $FilePath = "Index.php";
        public $StateManager;
        public $DefaultState = null;

        public $OutPutInEachRun = array();

        public $InputsType = array();

        public $BranchesID = array();

        public $FistLevelOfClusters = array();//each element is an abstractURLList ie.one cluster at level one
        public $ReachedAbstractURL;

        public $ExceptionLogger = null;


        public $time_start = null;//StartTime of Algorithm

        public function __construct()
        {
            ITOArray::$MaxArraySize = CommonConfig::$MaxArraySize;
            ini_set('memory_limit', CommonConfig::$MemLimit);
        }

        public abstract function InitializeInputListType();

        public abstract function PageUnderTest();

        public abstract function GenerateTestData();

        public function AddReachedAbstractUrlInEachRun($FilePath, $UrlType, $ListOfParams, $state)
        {

            $r = new AbstractURl($FilePath, $UrlType, $ListOfParams, $state);
            foreach ($ListOfParams as $ParamName => $ParamValue)
                {
                $ParamName = trim($ParamName);
                if (array_key_exists($ParamName, $this->InputsType))
                    {
                    // print("\n ***p: ".$ParamName. "***\n");
                    $input = (object)$this->InputsType[$ParamName];
                    //                print_r($input);
                    if (!empty($input) && $input != null)
                        {
                        $r->addDefaultValuesTo($ParamName, $input->GetCopyOfDefaultValueArrayObjects());
                        }
                    }
                }
            $this->ReachedAbstractURL[$r->Id] = $r;

        }

        public function UpdateFirstLevelClusterOfAbstractURL(State $State)
        {
            foreach ($this->ReachedAbstractURL as $AbstractUrlObject)
                {
                $AbstractUrl = (object)$AbstractUrlObject;
                $AbstractUrl->setState($State);
                $ClusterId = $AbstractUrl->GetHashOfBaseUrlAndParamName();
                if (array_key_exists($ClusterId, $this->FistLevelOfClusters))
                    {
                    $Cluster = (object)$this->FistLevelOfClusters[$ClusterId];
                    $Cluster->AddNewAbstractUrl($AbstractUrl);
                    } else
                    {
                    $NewCluster = new ListOfAbstractUrls();
                    $NewCluster->AddNewAbstractUrl($AbstractUrl);

                    $this->FistLevelOfClusters[$ClusterId] = $NewCluster;

                    }
                }
        }

        public function RunPageUnderTestAndGetFitness(IGetSolution $Solution, EBLE $TargetBranch)
        {
            //only if time exit run
            if ((microtime(true) - $this->time_start) < CommonConfig::$MaxExecutionTime)
                {
                $fitness = PHP_INT_MAX;
                try
                    {
                    $this->DefaultState = $this->CoverageTable->GetCopyOfStateForBranch($TargetBranch);
                    $this->RunPageUnderTest($Solution);
                    foreach ($this->ExecutedBranchList as $ExecutedBranchObject)
                        {
                        $ExecutedBranch = (object)$ExecutedBranchObject;
                        if ($ExecutedBranch->getBranchSection() === $TargetBranch->getBranchSection() &&
                            $ExecutedBranch->getBranchId() === $TargetBranch->getBranchId()
                        )
                            {
                            $fitness = $ExecutedBranch->getFitness();
                            }
                        }
                    } catch (Exception $e)
                    {
                    $this->ExceptionLogger->WriteObject($e);
                    }
                $Solution->GetSolution()->fitness = $fitness;
                return $fitness;
                } else
                {
                echo "Time Expired";
                }
        }

        public function RunPageUnderTest(Solution $solution)
        {
            //             print "\nR_".$this->Result->NumberOfRun."_g:".$this->Result->NumberOfGeneration;
            $this->CurrentSolution = $solution;
            $this->LoginDiscovery();

            $this->StateManager->SetCurrentState($this->DefaultState);
            $this->ReachedAbstractURL = array();
            $this->StateManager->AddNewResourceToResourceSequence($this->FilePath, $this->CurrentSolution->GetStringOfInputsNameAndValues());
            $this->ConvertSolutionInputVectorToInputOfPageUnderTest($solution);

            //run
            $this->ExecutedBranchList = array();
            $this->Result->NumberOfRun++;
            $this->PageUnderTest();

            $State = $this->StateManager->GetCurrentState();
            //update coverage table
            $this->CoverageTable->UpdateInputDataAndFitnessForBranchForPageUnderTest($this->ExecutedBranchList, $this->CurrentSolution, $State);
            $this->UpdateFirstLevelClusterOfAbstractURL($State);
            gc_collect_cycles();
        }

        public function ConvertSolutionInputVectorToInputOfPageUnderTest(Solution $solution)
        {
            $_GET = array();
            $_POST = array();

            //in this section we can get check range value
            foreach ($this->CurrentInterface as $InputName => $InputType)
                {
                switch ($InputType)
                    {
                    case"GET":
                        $_GET[$InputName] = $solution->InputVector[$InputName];
                        $_SERVER['REQUEST_METHOD'] = "GET";
                        break;

                    case "POST":
                        $_POST[$InputName] = $solution->InputVector[$InputName];
                        $_SERVER['REQUEST_METHOD'] = "POST";
                        break;
                    default:
                        $_REQUEST[$InputName] = $solution->InputVector[$InputName];

                    }
                }
        }

        public function UPEL($BranchID, $BranchType, FFER $FitnessFunctionEvaluationResult)
        {
            $TB = new EBLE($BranchID . "_T", "T", $FitnessFunctionEvaluationResult->TFit);
            $FB = new EBLE($BranchID . "_F", "F", $FitnessFunctionEvaluationResult->FFit);
            $this->ExecutedBranchList[] = $TB;//add to list
            $this->ExecutedBranchList[] = $FB;
            return $FitnessFunctionEvaluationResult->Result;
        }

        public function SetStateManager(StateManager $stateManager)
        {
            $this->StateManager = $stateManager;
        }


        public function SetDefaultState(State $state)
        {
            $this->DefaultState = $state;
        }

        public function SetCurrentInterface($Interface)
        {
            $this->CurrentSolution->SetInterface($Interface);
            $this->CurrentInterface = $Interface;
        }

        public function SetInputValueInCurrentSolution($InputsNameAndValues)
        {
            $this->CurrentSolution->SetInputsValue($InputsNameAndValues);
        }

        public function AddDefaultValueToInputOfInterfaceFromAbstractURL($ArrayOfInputsNameDefaultValue)
        {
            foreach ($ArrayOfInputsNameDefaultValue as $ParamName => $ArrayOfDefaultValue)
                {
                $ParamName = trim($ParamName);
                if (array_key_exists($ParamName, $this->InputsType))
                    {
                    $input = (object)$this->InputsType[$ParamName];
//                   print("\n***m:" . $ParamName . "***\n");
                    if (!empty($input) && $input != null)
                        $input->SetCopyOfDefaultValueObjects($ArrayOfDefaultValue);
                    }
                }


        }

        public function InitialCurrentSolutionPageUnderTest($ArrayOfParamsNameValue)
        {
            $this->CurrentSolution = $this->CurrentSolution->SetInputsValue($ArrayOfParamsNameValue);
        }

        public function SetCoverageTable(CoverageTableRedisVersion $CT)
        {
            $this->CoverageTable = $CT;
        }

        public function AddBranchesID($ID)
        {
            $this->BranchesID[$ID . "_T"] = $ID . "_T";
            $this->BranchesID[$ID . "_F"] = $ID . "_F";
        }

        public function AddDefaultValueTo($InputName, $ValueForIt)
        {
            $InputName = trim($InputName);
            if (array_key_exists($InputName, $this->InputsType))
                {
                //            print("\n***".$InputName."***".__FILE__.__LINE__."\n");
                $Input = (object)$this->InputsType[$InputName];
                //            print_r($Input);


                $Input->AddDefaultValue($ValueForIt);
                }
        }

        public function ResetBranchesId()
        {
            $this->BranchesID = array();
        }

        public function CookiesAd($VarName, $Value, $ExpiredTime, $SendForURLStartWith = "")
        {
            if ($SendForURLStartWith === "")
                $SendForURLStartWith = $this->FilePath;
            $this->StateManager->CookiesStateManger->add($VarName, $Value, $this->FilePath, $SendForURLStartWith, $ExpiredTime);
        }

        public function LoginDiscovery()
        {

        }

        function destroy()
        {
            $this->ExecutedBranchList = array();
            $this->CurrentSolution = null;
            $this->DefaultState = null;
            $this->CurrentInterface = array();
            $this->InputsType = array();
            $this->BranchesID = array();
            $this->FistLevelOfClusters = array();
            $this->ReachedAbstractURL = array();
        }
    }