<?php
    /**
     * Created by PhpStorm.
     * User: computer
     * Date: 11/22/2016
     * Time: 09:58 PM
     */


    require_once "BaseDirectory.php";
    require_once $BASEDIROFPROJECT . "/TestDataGenerationAlgorithm/PageTDG.php";
    require_once $BASEDIROFPROJECT . "/TestDataGenerationAlgorithm/Components/Solution/VS_Solution.php";
    require_once $BASEDIROFPROJECT . "/TestDataGenerationAlgorithm/configs/VSConfig.php";

    require_once $BASEDIROFPROJECT . "/TestDataGenerationAlgorithm/VS/DotNetCall.php";

    abstract class VS_PageTDG extends PageTDG
    {
        public $GInv;
        public $CandidateSolutions;
        private $DotNetClass = null;

        public function __construct()
        {
            $this->GInv = VSConfig::GetGInv();
            $this->CurrentSolution = new VS_Solution();
            $this->DotNetClass = new DotNetCall();
        }


        public function GenerateTestData()
        {
            $this->Result = new TDGResultForPageUnderTest();
            $this->Result->NumberOfGeneration = 0;
            //  $time_start = microtime(true);//Start Time

            echo "</br>\n  Begining TDG for[F:" . $this->FilePath . ",I" . $this->CurrentSolution->GetStringOfInputsNameAndValues() . "]";

            //firstRun with Initial RandomValue
            $this->RunPageUnderTest($this->CurrentSolution);
            $this->RunPageUnderTest($this->GetNewRandomSolution());

            if (count($this->CurrentInterface) > 0)
                {

                $ArrayOfParamNames = array_keys($this->CurrentSolution->InputVector);
                $NumberOfRun = 1;
                $NumberOfInput = count($this->CurrentInterface);
                $CoverageImprove = true;
                $ReachedBranchList = null;
                $TargetedReachedBranch = null;
                $BestSolutionForCurrentBranch = null;
                $NumberOfOptimizedVariable = 0;
                $SelectedInputForMutation = null;
                $SelectedInputNameForMutation = "";
                $PreviousFitness = 0;
                $CurrentDistance = 0;

                while ($CoverageImprove)
                    {
                    $ReachedBranchList = $this->CoverageTable->GetAllReachedBranch($this->CurrentInterface, $this->BranchesID);
                    foreach ($ReachedBranchList as $TargetBranchObject)
                        {
                        $TargetedReachedBranch = (object)$TargetBranchObject;
                        $FitnessOfCurrentBranch = $TargetedReachedBranch->getFitness();
                        $NumberOfOptimizedVariable = 1;

                        $IndexOfSelectedInputForMutation = 0;
                        $BestSolutionForCurrentBranch = $this->CoverageTable->GetCopyOfBestSolutionForBranch($TargetedReachedBranch);

                        while ($NumberOfOptimizedVariable <= $NumberOfInput && $FitnessOfCurrentBranch > 0)
                            {
                            $SelectedInputNameForMutation = $ArrayOfParamNames[$IndexOfSelectedInputForMutation];
                            $SelectedInputForMutation = $this->InputsType[$SelectedInputNameForMutation];
                            $this->CurrentSolution = clone $BestSolutionForCurrentBranch;
                            $PreviousFitness = $this->CoverageTable->GetFitnessFor($TargetedReachedBranch);

                            if ($SelectedInputForMutation->IsNumeric())
                                {
//                                $NumberOfGenerationForEachReachedBranch = 0;
                                $dir = $this->ExploratoryMove($BestSolutionForCurrentBranch, $SelectedInputNameForMutation, $TargetedReachedBranch);

                                if ($dir != 0)
                                    {
                                    $CurrentDistance = $this->CoverageTable->GetFitnessFor($TargetedReachedBranch);

                                    $NumberOfGenerationForEachReachedBranch = 2;
                                    //pattern move
//                                     while ($CurrentDistance > 0 && $PreviousFitness > $CurrentDistance && $NumberOfGenerationForEachReachedBranch < VSConfig::$MaxGenerationNumber)
                                    while ($CurrentDistance > 0 && $NumberOfGenerationForEachReachedBranch < VSConfig::$MaxGenerationNumber)
                                        {
                                        $NumberOfGenerationForEachReachedBranch++;
                                        $PreviousFitness = $CurrentDistance;
                                        $this->PatternMove($BestSolutionForCurrentBranch, $dir, $CurrentDistance, $PreviousFitness, $SelectedInputNameForMutation, $NumberOfGenerationForEachReachedBranch, $TargetedReachedBranch);
                                        $this->Result->NumberOfGeneration = $this->Result->NumberOfGeneration + 1;
                                        }

                                    $IndexOfSelectedInputForMutation++;
                                    if ($IndexOfSelectedInputForMutation >= $NumberOfInput)
                                        {
                                        $IndexOfSelectedInputForMutation = 0;
                                        }
                                    }
                                } else
                                {

                                $NumberOfGenerationForEachReachedBranch = 0;
                                //pattern move
                                while ($CurrentDistance > 0 && $NumberOfGenerationForEachReachedBranch < VSConfig::$MaxGenerationNumber)
//                                        while ($CurrentDistance > 0 && $PreviousFitness > $CurrentDistance && $NumberOfGenerationForEachReachedBranch < VSConfig::$MaxGenerationNumber)
                                    {
                                    $newValue = null;
                                    if ($SelectedInputForMutation->getInputType() === VariableType::STRING)
                                        {
                                        $newValue = $SelectedInputForMutation->GetNeighboursOfThisWithSeededAndRandomValue(VSConfig::$NumberOfRandomString);
                                        } else if ($SelectedInputForMutation->getInputType() === VariableType::_ARRAY)
                                        {
                                        $newValue = array();
                                        for ($i = 0; $i < VSConfig::$PopulationSizeForArrayInput; $i++)
                                            {
                                            $newValue[] = $SelectedInputForMutation->GetRandomValue();
                                            }
                                        } else//CustomInput
                                        {
                                        $newValue = array();
                                        for ($i = 0; $i < $SelectedInputForMutation->NumberInEachGeneration; $i++)
                                            {
                                            $newValue[] = $SelectedInputForMutation->GetRandomValue();
                                            }
                                        }
                                    $this->CandidateSolutions = $BestSolutionForCurrentBranch->GetNewSolutionsWithValueFor($SelectedInputNameForMutation, $newValue);
                                    $this->Result->NumberOfGeneration = $this->Result->NumberOfGeneration + 1;

                                    $PreviousFitness = $CurrentDistance;
                                    $BestSolutionForCurrentBranch = $this->RunCandidateSolutionAndGetBestSolution($BestSolutionForCurrentBranch, $TargetedReachedBranch, $CurrentDistance);
                                    $NumberOfGenerationForEachReachedBranch++;

                                    }
                                }

                            $CurrentFit = $this->CoverageTable->GetFitnessFor($TargetedReachedBranch);
                            if ($CurrentFit > 0 && $CurrentFit < $PreviousFitness)
                                {
                                $NumberOfOptimizedVariable = 1;
                                } else
                                {
                                $NumberOfOptimizedVariable++;
                                }

                            $IndexOfSelectedInputForMutation = $IndexOfSelectedInputForMutation % $NumberOfInput;
                            $FitnessOfCurrentBranch = $this->CoverageTable->GetFitnessFor($TargetedReachedBranch);
                            // $this->Result->NumberOfGeneration += $NumberOfGenerationForEachReachedBranch;
                            }


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


        public function PatternMove(&$BestSolution, &$dir, &$CurrentDistance, $PreviousDistance, $SelectedInputNameForMutation, $NumberOfGenerationForEachReachedBranch, $TargetedReachedBranch)
        {
            $this->CandidateSolutions = array();
            $Size = VSConfig::$PopulationSizeForNumericInput;
            $CurrentValue = $BestSolution->GetInputValue($SelectedInputNameForMutation);
            $Input = $this->InputsType[$SelectedInputNameForMutation];
            $R = $Input->r * $this->GInv[$NumberOfGenerationForEachReachedBranch];

            if ($dir === +1)
                {
                $Std = $R / pow(2, $NumberOfGenerationForEachReachedBranch - 1);
                $result = null;
                $Mean = $CurrentValue;

                if ($Input->getInputType() === VariableType::DOUBLE)
                    {
                    $result = $this->DotNetClass->GetNormalDouble($Size, $Mean, $Std);
                    } else if ($Input->getInputType() === VariableType::INTEGER)
                    {
                    $result = $this->DotNetClass->GetNormalInt($Size, $Mean, $Std);
                    }
                $result = array_unique($result);
                $this->Result->NumberOfGeneration = $this->Result->NumberOfGeneration + 1;
                $this->CandidateSolutions = $BestSolution->GetNewSolutionsWithValueFor($SelectedInputNameForMutation, $result, true);
                } else if ($dir === -1)
                {
                $Std = $R / pow(2, $NumberOfGenerationForEachReachedBranch - 1);
                $result = null;
                //    $Mean = $CurrentValue - ($CurrentValue + $R) / 2;
                $Mean = $CurrentValue;

                if ($Input->getInputType() === VariableType::DOUBLE)
                    {
                    $result = $this->DotNetClass->GetNormalDouble($Size, $Mean, $Std);
                    } else if ($Input->getInputType() === VariableType::INTEGER)
                    {
                    $result = $this->DotNetClass->GetNormalInt($Size, $Mean, $Std);
                    }
                $result = array_unique($result);
                $this->Result->NumberOfGeneration = $this->Result->NumberOfGeneration + 1;
                $this->CandidateSolutions = $BestSolution->GetNewSolutionsWithValueFor($SelectedInputNameForMutation, $result, true);
                }


            $CurrentDistance = PHP_INT_MAX;
            foreach ($this->CandidateSolutions as $Solution)
                {
                if ((microtime(true) - $this->time_start) > CommonConfig::$MaxExecutionTime)
                    break;
                $Solution->fitness = $this->RunPageUnderTestAndGetFitness($this->CurrentSolution, $TargetedReachedBranch);
                if ($CurrentDistance > $Solution->fitness)
                    {
                    $CurrentDistance = $Solution->fitness;
                    }
                //update bestSolution
                if ($BestSolution->fitness >= $Solution->fitness)
                    {
                    $BestSolution = $Solution;
                    }
                }


            if ($CurrentValue > $BestSolution->GetInputValue($SelectedInputNameForMutation))
                {
                $dir = -1;
                } else if ($CurrentValue < $BestSolution->GetInputValue($SelectedInputNameForMutation))
                {
                $dir = +1;
                }

            //fitness not improved
            if ($PreviousDistance < $CurrentDistance)
                {
                $dir = 0;
                }
        }

        public function ExploratoryMove(&$BestSolution, $NameOfSelectedInputForMutation, EBLE $TargetBranch)
        {
            $dir = 0;
            $NumberOfGenerationForEachReachedBranch = 1;

            $this->CandidateSolutions = array();
            $Size = VSConfig::$PopulationSizeForNumericInput;


            $Input = $this->InputsType[$NameOfSelectedInputForMutation];
            $CurrentValue = $Input->r;//[a,b]=>c=[b-a]/2
//           $CurrentValue=$BestSolution->GetInputValue($NameOfSelectedInputForMutation);
            $R = $Input->r * $this->GInv[$NumberOfGenerationForEachReachedBranch];

            $Mean = $CurrentValue + ($R / 2);
            $Std = $R / 2;//seach in nesf baze

            $result = null;
            if ($Input->getInputType() === VariableType::DOUBLE)
                {
                $result = $this->DotNetClass->GetNormalDouble($Size, $Mean, $Std);
                } else if ($Input->getInputType() === VariableType::INTEGER)
                {
                $result = $this->DotNetClass->GetNormalInt($Size, $Mean, $Std);
                }
            //random uniform value
            $newValue = array();
            for ($i = 0; $i < VSConfig::$PopulationSizeForNumericInput; $i++)
                {
                $newValue[] = $Input->GetRandomValue();
                }
            $result=array_merge($result,$newValue);
            $result = array_unique($result);
            $this->CandidateSolutions = $BestSolution->GetNewSolutionsWithValueFor($NameOfSelectedInputForMutation, $result, true);
            $this->Result->NumberOfGeneration++;

            $PreviousDistance = $TargetBranch->getFitness();
            foreach ($this->CandidateSolutions as $Solution)
                {
                if ((microtime(true) - $this->time_start) > CommonConfig::$MaxExecutionTime)
                    break;

                $CurrentDistance = $this->RunPageUnderTestAndGetFitness($Solution, $TargetBranch);
                if ($CurrentDistance < $PreviousDistance)
                    {
                    $dir = +1;
                    $BestSolution = $Solution;
                    }
                }

            //positive section not affect fitness
            if ($dir === 0)
                {
                $this->CandidateSolutions = array();
                $Mean = $CurrentValue - ($R / 2);
                $result = null;
                if ($Input->getInputType() === VariableType::DOUBLE)
                    {
                    $result = $this->DotNetClass->GetNormalDouble($Size, $Mean, $Std);
                    } else if ($Input->getInputType() === VariableType::INTEGER)
                    {
                    $result = $this->DotNetClass->GetNormalInt($Size, $Mean, $Std);
                    }

                //random uniform value
                $newValue = array();
                for ($i = 0; $i < VSConfig::$PopulationSizeForNumericInput; $i++)
                    {
                    $newValue[] = $Input->GetRandomValue();
                    }
                $result=array_merge($result,$newValue);
                $result = array_unique($result);
                $this->CandidateSolutions = $BestSolution->GetNewSolutionsWithValueFor($NameOfSelectedInputForMutation, $result, true);
                $this->Result->NumberOfGeneration++;

                foreach ($this->CandidateSolutions as $Solution)
                    {
                    if ((microtime(true) - $this->time_start) > CommonConfig::$MaxExecutionTime)
                        break;

                    $CurrentDistance = $this->RunPageUnderTestAndGetFitness($Solution, $TargetBranch);
                    if ($CurrentDistance < $PreviousDistance)
                        {
                        $dir = -1;
                        $BestSolution = $Solution;
                        }
                    }
                }
            return $dir;
        }

        public
        function RunCandidateSolutionAndGetBestSolution(VS_Solution $BestSolutionForBranchUnderTDG, $TargetedReachedBranch, &$CurrentDistance)
        {
            $CurrentDistance = PHP_INT_MAX;
            foreach ($this->CandidateSolutions as $Solution)
                {
                if ((microtime(true) - $this->time_start) > CommonConfig::$MaxExecutionTime)
                    break;

                $Solution->fitness = $this->RunPageUnderTestAndGetFitness($Solution, $TargetedReachedBranch);
                if ($BestSolutionForBranchUnderTDG->fitness > $Solution->fitness)
                    {
                    $BestSolutionForBranchUnderTDG = $Solution;
                    }
                if ($Solution->fitness < $CurrentDistance)
                    {
                    $CurrentDistance = $Solution->fitness;
                    }
                //            if ($BestSolutionForBranchUnderTDG->fitness <= 0)
                //                break;
                }
            return $BestSolutionForBranchUnderTDG;
        }

        public function GetNewRandomSolution()
        {
            $NewSolution = clone $this->CurrentSolution;
            foreach ($this->CurrentInterface as $ParamName => $ParamRequestType)
                {
                $Input = (object)$this->InputsType[$ParamName];
                $NewSolution->SetInputValue($ParamName, $Input->GetRandomValue());
                }
            return $NewSolution;
        }


        function destroy()
        {

        }
    }















