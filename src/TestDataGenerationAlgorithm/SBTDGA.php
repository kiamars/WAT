<?php

/**
 * Created by PhpStorm.
 * User: Mirzaee
 * Date: 10/24/2016
 * Time: 6:33 PM
 */
require_once "BaseDirectory.php";
require_once $BASEDIROFPROJECT . "/ExternalLibs/vendor/autoload.php";
require_once $BASEDIROFPROJECT . "/Utility/LogSystem/LogToFile.inc";
require_once $BASEDIROFPROJECT . "/TestDataGenerationAlgorithm/configs/PSOConfig.php";
require_once $BASEDIROFPROJECT . "/TestDataGenerationAlgorithm/Components/Result/ApplicationUnderTestResult.inc";
require_once $BASEDIROFPROJECT . "/TestDataGenerationAlgorithm/Components/TestSequenceAndTestData/AbstractURl.php";
require_once $BASEDIROFPROJECT . "/TestDataGenerationAlgorithm/IPageObjectResolver.php";
require_once $BASEDIROFPROJECT . "/TestDataGenerationAlgorithm/Components/StateManager/StateManager.php";
//require_once $BASEDIROFPROJECT . "/TestDataGenerationAlgorithm/Components/UserSimulator/UserSimulator.php";
require_once $BASEDIROFPROJECT . "/TestDataGenerationAlgorithm/Components/UserSimulator/UserSimulatorRedisVersion.php";
//require_once $BASEDIROFPROJECT . "/TestDataGenerationAlgorithm/Components/CoverageTable/CoverageTable.inc";
require_once $BASEDIROFPROJECT . "/TestDataGenerationAlgorithm/Components/CoverageTable/CoverageTableUsingRedis.php";
require_once $BASEDIROFPROJECT . "/TestDataGenerationAlgorithm/Components/UserSimulator/AccountManager/AccountManager.php";

class SBTDGA
{
    public  $pageObjectResolver;//static
    public  $StateManager = null;//static
    public  $userSimulator = null;//static
    public  $CoverageTable = null;//static

    public $applicationUnderTestResult = null;
    public $ExceptionLogger = null;

    public $PercentOfCoverageInEachRun = "";

    public function __construct(IReSolver $pageObjectResolver, $NumberOfBranchesInAllPages, $ApplicationUnderTestName, $AlgorithmName = "PSO")
    {
        ini_set('memory_limit', CommonConfig::$MemLimit);
        $this->Initializ($pageObjectResolver, $NumberOfBranchesInAllPages);

        switch ($AlgorithmName) {
            case"PSO":
                $this->applicationUnderTestResult->AlgorithmConfig = PSOConfig::GetString();
                break;
            case"ACO":
                $this->applicationUnderTestResult->AlgorithmConfig = ACOConfig::GetString();
                break;
            case"AVM":
                $this->applicationUnderTestResult->AlgorithmConfig = AVMConfig::GetString();
                break;
            case"VS":
                $this->applicationUnderTestResult->AlgorithmConfig = VSConfig::GetString();
                break;
        }

        $this->applicationUnderTestResult->SearchAlgorithm = $AlgorithmName;
        $this->applicationUnderTestResult->ApplicationUnderTestName = $ApplicationUnderTestName;
        CommonConfig::$ResultRootDirectory = CommonConfig::$ResultRootDirectory . "\\" . $this->applicationUnderTestResult->SearchAlgorithm . "\\" . $this->applicationUnderTestResult->ApplicationUnderTestName . "\\" . date("Y-m-d-H-i-s");
        CommonConfig::SetDataBaseBackUpDirectory(CommonConfig::$ResultRootDirectory . "\\" . "DatabaseBackups");
        $this->ExceptionLogger = new  LogToFile(CommonConfig::$ResultRootDirectory, ".ExceptionLog",".json");
    }

    public  function Initialisation($FilePath, $ReqType, $ParamNameVal)
    {
        //SetFirstAbstractURL and Initial S$tate
        $this->userSimulator->Initialise($FilePath, $ReqType, $ParamNameVal, $this->StateManager->GetCurrentState());
    }

    public function GenerateTestSequenceAndData()
    {
        $time_start = microtime(true);//Start Time
        do {

            $percentOfCoverage = $this->CoverageTable->GetPercentOfCoverage();
            $this->PercentOfCoverageInEachRun .= $this->applicationUnderTestResult->NumberOfRuns . ";" . $percentOfCoverage . "\n";
            $this->applicationUnderTestResult->PercentOfCoverageInTime .= (microtime(true) - $time_start) . ":" . $percentOfCoverage . "\n";
            print "\nR" . $this->applicationUnderTestResult->NumberOfRuns ." G".$this->applicationUnderTestResult->NumberOfGeneration." memory_get_usage:" . memory_get_usage() . "PC" . $percentOfCoverage . "\n";
            if ($percentOfCoverage >= 100)
                break;

            $AbstractUrl = $this->userSimulator->GetNextAbstractUrl();
            if ($AbstractUrl !== null) {

                $PageObject = $this->pageObjectResolver->Resolve($AbstractUrl->BaseURL);
                if ($PageObject != null) {
                    $PageObject->time_start=$time_start;
                    ini_set('memory_limit', CommonConfig::$MemLimit);
                    $PageObject->ExceptionLogger = $this->ExceptionLogger;
                    $PageObject->SetStateManager($this->StateManager);
                    $PageObject->SetCoverageTable($this->CoverageTable);
                    $PageObject->SetDefaultState($AbstractUrl->getState());
                    $this->SetInterfaceAndParamNameValue($PageObject, $AbstractUrl);
                    $PageObject->AddDefaultValueToInputOfInterfaceFromAbstractURL($AbstractUrl->GetDefaultValuesArrayForUrlParams());

                    $PageObject->ResetBranchesId();

                    //$PageObject->GenerateTestDataV1();
                    $PageObject->GenerateTestData();

                    $this->StateManager->PathOfPageUnderTest=$AbstractUrl->BaseURL;
                    $this->userSimulator->UpdateOperateAbleUserActions($PageObject->FistLevelOfClusters);

                    $this->UpdateResult($PageObject->Result);
                }
                //  print_r($PageObject->Result);
            } else {
                break;// No url exist
            }
        } while ((microtime(true) - $time_start) < CommonConfig::$MaxExecutionTime);
        // $CoverageImprove=$this->CoverageTable->CheckForCoverageImproveAndUpdateLastFit(); and with while conditi

        $this->applicationUnderTestResult->ExecutionTime = (microtime(true) - $time_start);
        $this->applicationUnderTestResult->PercentageOfCoverage = $this->CoverageTable->GetPercentOfCoverage();
        $this->WriteResultInFile();
    }


    public function SetInterfaceAndParamNameValue(PageTDG $PageObject, AbstractURl $AbstractUrl)
    {
        $Interface = array();
        $Interface = $AbstractUrl->GetInterface();
        $PageObject->SetCurrentInterface($Interface);

        $ParamNameAndValue = array();
        $ParamNameAndValue = $AbstractUrl->GetParamsNameValue();
        $PageObject->SetInputValueInCurrentSolution($ParamNameAndValue);

        $CallInterface="CallInterface[";
        foreach($Interface as $ParamName=>$ParamType)
            {
            $CallInterface.="(";
            $CallInterface.="N: ".$ParamName;
            $CallInterface.=",T: ".$ParamType;
            $CallInterface.=",V: ".$ParamNameAndValue[$ParamName];
            $CallInterface.="),";
            }
        $CallInterface.="]";
        echo"</br>\n  Begining TDG for[F:".$AbstractUrl->BaseURL.",".$CallInterface.",".$this->StateManager->CookiesStateManger->GetCookieInterfaceAndValueFor($AbstractUrl->BaseURL)."]";
       // print_r($ParamNameAndValue);
    }

    public function SetDBConnection($db_host, $db_user, $db_pass, $db_name)
    {
        CommonConfig::SetDataBaseConnection($db_host, $db_user, $db_pass, $db_name);
    }

    public function UpdateResult(TDGResultForPageUnderTest $pageResult)
    {
        $this->applicationUnderTestResult->NumberOfRuns += $pageResult->NumberOfRun;
        $this->applicationUnderTestResult->NumberOfGeneration += $pageResult->NumberOfGeneration;
    }


    private function WriteResultInFile()
    {

        $this->applicationUnderTestResult->ReachedUrlAndNumberOfUseThemForGenerateTestData = $this->userSimulator->GetReachedURLsAndNumberOfUseThem();

        // $this->applicationUnderTestResult->CoverageTable = self::$CoverageTable;
        $this->applicationUnderTestResult->CoverageTable = $this->CoverageTable->GetCoverageTable();
        $this->applicationUnderTestResult->NumberOfCoveredBranches = $this->CoverageTable->GetNumberOfCoveredBranches();
        $this->applicationUnderTestResult->PercentOfCoverageInEachRun = $this->PercentOfCoverageInEachRun;
        $Loger = new  LogToFile(CommonConfig::$ResultRootDirectory, $this->applicationUnderTestResult->ApplicationUnderTestName. $this->applicationUnderTestResult->PercentageOfCoverage, ".json");

        $ser = new Services_JSON(SERVICES_JSON_USE_TO_JSON);
        $text = $ser->encode($this->applicationUnderTestResult);

        if (empty($text))
            $text = print_r($this->applicationUnderTestResult);

        $Loger->WriteResult($text);
        $this->WriteUserSimulatorToFile();

        echo "\n</br>the result is writed in Directory:" . $Loger->RootDirectory . "FileName:" . $Loger->FileName;

    }

    public function WriteUserSimulatorToFile()
    {
        $Loger = new  LogToFile(CommonConfig::$ResultRootDirectory, "UserSimulator" . $this->applicationUnderTestResult->ApplicationUnderTestName, ".json");

        $ser = new Services_JSON(SERVICES_JSON_USE_TO_JSON);
        $text = $ser->encode($this->userSimulator);

        if (empty($text))
            $text = print_r($this->applicationUnderTestResult);

        $Loger->WriteResult($text);
    }
    public function GetResultAsString()
    {
        $R="";
        // " AlgorithmName;ApplicationName;NumberOfCoveredBranches;PercentOfCoverage;NumberOfRuns;NumberOfGenerations;AlgorithmConfig\n";
        $R.=$this->applicationUnderTestResult->SearchAlgorithm;
        $R.=";".$this->applicationUnderTestResult->ApplicationUnderTestName;
        $R.=";".$this->applicationUnderTestResult->NumberOfCoveredBranches;
        $R.=";".$this->applicationUnderTestResult->PercentageOfCoverage;
        $R.=";".$this->applicationUnderTestResult->NumberOfRuns;
        $R.=";".$this->applicationUnderTestResult->NumberOfGeneration;
        $R.=";".$this->applicationUnderTestResult->AlgorithmConfig;
        $R.="\n";
        return $R;
    }

    /**
     * @param IReSolver $pageObjectResolver
     * @param $NumberOfBranchesInAllPages
     */
    public function Initializ(IReSolver $pageObjectResolver, $NumberOfBranchesInAllPages)
    {
        $this->StateManager = new StateManager();
        $this->userSimulator = new UserSimulatorRedisVersion();
        $this->CoverageTable = new CoverageTableRedisVersion($NumberOfBranchesInAllPages);
        $this->pageObjectResolver = $pageObjectResolver;
        $this->applicationUnderTestResult = new ApplicationUnderTestResult();
    }

}


