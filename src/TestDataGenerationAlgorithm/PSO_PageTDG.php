<?php
    /**
     * Created by PhpStorm.
     * User: Mirzaee
     * Date: 11/2/2016
     * Time: 9:10 PM
     */
    require_once "PageTDG.php";
    require_once $BASEDIROFPROJECT . "/TestDataGenerationAlgorithm/Components/Solution/PSO_Solution.php";

    abstract class PSO_PageTDG extends PageTDG
    {
        public $Population = array();
        public $GBest;
        public $VMax = 1;

        public function __construct()
        {
            $this->CurrentSolution = new PSO_Solution();
        }

        public function GenerateTestData()
        {
            $this->Result = new TDGResultForPageUnderTest();

            //  $time_start = microtime(true);//Start Time


            //firstRun with Initial RandomValue
            $this->RunPageUnderTest($this->CurrentSolution);
            $this->RunPageUnderTest($this->GetNewRandomSolution());

            //LocalVariable
            $ReachedBranchList = null;
            $BestSolutionForBranchUnderTDG = null;
            $CSs = null;//ListOfCandidateSolution

            if (count($this->CurrentInterface) > 0)
                {
                $NumberOfRun = 1;

                $CoverageImprove = true;
                while ($CoverageImprove)
                    {
                    $ReachedBranchList = $this->CoverageTable->GetAllReachedBranch($this->CurrentInterface, $this->BranchesID);

                    foreach ($ReachedBranchList as $TargetBranchObject)
                        {

                        $TargetedReachedBranch = (object)$TargetBranchObject;

                        $FitnessOfCurrentBranch = $TargetedReachedBranch->getFitness();


                        $this->InitialFirstPopulation();
                        $this->GBest = $this->CoverageTable->GetCopyOfBestSolutionForBranch($TargetedReachedBranch);
                        $this->GBest->fitness = $FitnessOfCurrentBranch;

                        $NumberOfGenerationForEachReachedBranch = 0;
                        while ($FitnessOfCurrentBranch > 0 && $NumberOfGenerationForEachReachedBranch < PSOConfig::$MaxGenerationNumber)
                            {

                            //Update PBest
                            $this->UpdatePBest($TargetedReachedBranch);

                            //Update GBest
                            $this->CalculateGBest();

                            //update Velocity and Position of each particle
                            $this->UpdateVelocityANdPosition();


                            $FitnessOfCurrentBranch = $this->CoverageTable->GetFitnessFor($TargetedReachedBranch);
                            $NumberOfGenerationForEachReachedBranch++;
                            }
                        $this->Result->NumberOfGeneration += $NumberOfGenerationForEachReachedBranch;

                        if ($this->CoverageTable->IsPageUnderTestFullCovered($this->BranchesID))
                            {
                            break;
                            }
                        }
                    $NumberOfRun = $NumberOfRun + $this->Result->NumberOfRun;
                    $CoverageImprove = $this->CoverageTable->CheckForCoverageImproveAndUpdateLastFitForPageUnderTest($this->BranchesID);
                    }
                }

            //  $this->Result->ExecutionTime= (microtime(true) - $time_start);//Execution Time
            //   $this->Result->PercentageOfCoverage=$this->CoverageTable->GetPercentageOfCoverageForPageUnderTest($this->BranchesID);
            //    $this->Result->Interface=$this->CurrentInterface;
            //    $this->Result->PageUnderTestPath=$this->FilePath;
            //    $this->Result->RowState=$this->CoverageTable->GetRowStateForPageUnderTest($this->BranchesID);
        }

        public function GenerateTestDataV1()
        {
            $this->Result = new TDGResultForPageUnderTest();

            $time_start = microtime(true);//Start Time

            echo "</br>\n  Begining TDG for[F:" . $this->FilePath . "," . $this->CurrentSolution->GetStringOfInputsNameAndValues() . "";

            //firstRun with Initial RandomValue
            $this->RunPageUnderTest($this->CurrentSolution);


            //LocalVariable
            $ReachedBranchList = null;
            $BestSolutionForBranchUnderTDG = null;
            $CSs = null;//ListOfCandidateSolution

            if (count($this->CurrentInterface) > 0)
                {
                $NumberOfRun = 1;

                $ReachedBranchList = $this->CoverageTable->GetAllReachedBranch($this->CurrentInterface, $this->BranchesID);

                foreach ($ReachedBranchList as $TargetBranchObject)
                    {

                    $TargetedReachedBranch = (object)$TargetBranchObject;

                    $FitnessOfCurrentBranch = $TargetedReachedBranch->getFitness();


                    $this->InitialFirstPopulation();
                    $this->GBest = $this->CoverageTable->GetCopyOfBestSolutionForBranch($TargetedReachedBranch);
                    $this->GBest->fitness = $FitnessOfCurrentBranch;

                    $NumberOfGenerationForEachReachedBranch = 0;
                    while ($FitnessOfCurrentBranch > 0 && $NumberOfGenerationForEachReachedBranch < PSOConfig::$MaxGenerationNumber)
                        {

                        //Update PBest
                        $this->UpdatePBest($TargetedReachedBranch);

                        //Update GBest
                        $this->CalculateGBest();

                        //update Velocity and Position of each particle
                        $this->UpdateVelocityANdPosition();


                        $FitnessOfCurrentBranch = $this->CoverageTable->GetFitnessFor($TargetedReachedBranch);
                        $NumberOfGenerationForEachReachedBranch++;
                        }
                    $this->Result->NumberOfGeneration += $NumberOfGenerationForEachReachedBranch;
                    }
                $NumberOfRun = $NumberOfRun + $this->Result->NumberOfRun;

                }

            $this->Result->ExecutionTime = (microtime(true) - $time_start);//Execution Time
            $this->Result->PercentageOfCoverage = $this->CoverageTable->GetPercentageOfCoverageForPageUnderTest($this->BranchesID);
            $this->Result->Interface = $this->CurrentInterface;
            $this->Result->PageUnderTestPath = $this->FilePath;
            $this->Result->RowState = $this->CoverageTable->GetRowStateForPageUnderTest($this->BranchesID);
        }

        public function CalculateGBest()
        {
            for ($i = 0; $i < count($this->Population); $i++)
                {
                $PsoParticle = (object)$this->Population[$i];
                if ($PsoParticle->PBest->fitness < $this->GBest->fitness)
                    {
                    $this->GBest = clone $PsoParticle;
                    }
                }
        }

        public function UpdatePBest(EBLE $TargetBranch)
        {
            for ($i = 0; $i < count($this->Population); $i++)
                {
                $Solution = (object)$this->Population[$i];
                if ((microtime(true) - $this->time_start) > CommonConfig::$MaxExecutionTime)
                    break;
                $Solution->fitness = $this->RunPageUnderTestAndGetFitness($Solution, $TargetBranch);

                //update PBest
                if ($Solution->fitness < $Solution->PBest->fitness)
                    {
                    $Solution->PBest = clone $Solution;
                    }
                }
        }

        public function UpdateVelocityANdPosition()
        {

            for ($i = 0; $i < count($this->Population); $i++)
                {
                $PsoSolution = (object)$this->Population[$i];
                foreach ($this->CurrentInterface as $ParamName => $ParamRequestType)
                    {
                    $ParamName = trim($ParamName);
                    if (array_key_exists($ParamName, $this->InputsType))
                        {
                        $Input = (object)$this->InputsType[$ParamName];
                        //$this->GBest,$this->VMax
                        if ($Input->getInputType() === VariableType::DOUBLE || $Input->getInputType() === VariableType::INTEGER)
                            {
                            $this->VMax = ($Input->Max - $Input->Min) / 2;

                            $PBestI = $PsoSolution->PBest->GetInputValue($ParamName);

                            $GBestI = $this->GBest->GetInputValue($ParamName);

                            $XI = $PsoSolution->GetInputValue($ParamName);

                            $NewValueOfVelocityI = $PsoSolution->Velocity[$ParamName];
                            $NewValueOfVelocityI = PsoConfig::$w * $NewValueOfVelocityI +
                                PsoConfig::$c1 * (mt_rand() / mt_getrandmax()) * ($PBestI - $XI) +
                                PsoConfig::$c2 * (mt_rand() / mt_getrandmax()) * ($GBestI - $XI);
                            if ($NewValueOfVelocityI > $this->VMax)
                                {
                                $NewValueOfVelocityI = $this->VMax;
                                } else if ($NewValueOfVelocityI < (-1 * $this->VMax))
                                {
                                $NewValueOfVelocityI = -1 * $this->VMax;
                                }
                            $PsoSolution->Velocity[$ParamName] = $NewValueOfVelocityI;

                            //update position
                            if ($Input->getInputType() === VariableType::INTEGER)
                                $PsoSolution->SetInputValue($ParamName, intval($XI + $NewValueOfVelocityI));
                            else
                                $PsoSolution->SetInputValue($ParamName, $XI + $NewValueOfVelocityI);

                            } else if ($Input->getInputType() === VariableType::STRING)
                            {
                            $Input->SetCurrentValue($PsoSolution->GetInputValue($ParamName));
                            $PsoSolution->SetInputValue($ParamName, $Input->GetRandomValue());
                            } elseif ($Input->getInputType() === VariableType::_ARRAY)
                            {
                            $PsoSolution->SetInputValue($ParamName, $Input->GetRandomValue());
                            } else
                            {
                            $PsoSolution->SetInputValue($ParamName, $Input->GetRandomValue());
                            }
                        }

                    }
                }
        }

        public function GetNewRandomSolution()
        {
            //for numeric set velocity
            foreach ($this->CurrentInterface as $ParamName => $ParamRequestType)
                {
                $ParamName = trim($ParamName);
                if (array_key_exists($ParamName, $this->InputsType))
                    {
                    $Input = (object)$this->InputsType[$ParamName];
                    if ($Input->getInputType() === VariableType::DOUBLE || $Input->getInputType() === VariableType::INTEGER)
                        {
                        $this->CurrentSolution->Velocity[$ParamName] = 1;
                        }
                    } else
                    {
                    print"\n in " . __FILE__ . __LINE__ . "InputName" . $ParamName . "NotFound";
                    }

                }

            $NewSolution = clone $this->CurrentSolution;
            foreach ($this->CurrentInterface as $ParamName => $ParamRequestType)
                {
                if (array_key_exists($ParamName, $this->InputsType))
                    {
                    $Input = (object)$this->InputsType[$ParamName];
                    $NewSolution->SetInputValue($ParamName, $Input->GetRandomValue());
                    }
                }
            return $NewSolution;
        }

        public function  InitialFirstPopulation()
        {
            //for numeric set velocity
            foreach ($this->CurrentInterface as $ParamName => $ParamRequestType)
                {
                $ParamName = trim($ParamName);
                if (array_key_exists($ParamName, $this->InputsType))
                    {
                    $Input = (object)$this->InputsType[$ParamName];
                    if ($Input->getInputType() === VariableType::DOUBLE || $Input->getInputType() === VariableType::INTEGER)
                        {
                        $this->CurrentSolution->Velocity[$ParamName] = 1;
                        }
                    }
                }

            $this->Population = array();
            foreach ($this->CurrentInterface as $ParamName => $ParamRequestType)
                {
                $ParamName = trim($ParamName);
                if (array_key_exists($ParamName, $this->InputsType))
                    {
                    $Input = (object)$this->InputsType[$ParamName];
                    if ($Input->getInputType() === VariableType::DOUBLE || $Input->getInputType() === VariableType::INTEGER)
                        {
                            {
                            $newValue = array();
                            for ($i = 0; $i < PSOConfig::$PopulationSizeForNumericInput; $i++)
                                {
                                $newValue[] = $Input->GetRandomValue();
                                }
                            }
                        $this->Population = array_merge($this->Population, $this->CurrentSolution->GetNewSolutionsWithValueFor($ParamName, $newValue, true));
                        } else if ($Input->getInputType() === VariableType::STRING)
                        {
                        $newValue = $Input->GetNeighboursOfThisWithSeededAndRandomValue(PSOConfig::$NumberOfRandomString);
                        $this->Population = array_merge($this->Population, $this->CurrentSolution->GetNewSolutionsWithValueFor($ParamName, $newValue));
                        } else if ($Input->getInputType() === VariableType::_ARRAY)
                        {
                        $newValue = array();
                        for ($i = 0; $i < PSOConfig::$PopulationSizeForArrayInput; $i++)
                            {
                            $newValue[] = $Input->GetRandomValue();
                            }
                        $this->Population = array_merge($this->Population, $this->CurrentSolution->GetNewSolutionsWithValueFor($ParamName, $newValue));
                        } else//Custom Input
                        {
                        $newValue = array();
                        for ($i = 0; $i < $Input->NumberInEachGeneration; $i++)
                            {
                            $newValue[] = $Input->GetRandomValue();
                            }
                        $this->Population = array_merge($this->Population, $this->CurrentSolution->GetNewSolutionsWithValueFor($ParamName, $newValue));
                        }
                    }
                }
        }

        function destroy()
        {
            $this->Population = array();
            $this->GBest = null;
        }
    }
