<?php
    /**
     * Created by PhpStorm.
     * User: Mirzaee
     * Date: 11/2/2016
     * Time: 9:10 PM
     */
    require_once "BaseDirectory.php";
    require_once $BASEDIROFPROJECT . "/TestDataGenerationAlgorithm/AVMTDG/AVMMutationOprator.php";
    require_once $BASEDIROFPROJECT . "/TestDataGenerationAlgorithm/AVMTDG/AVM_PageTDG.php";
    require_once $BASEDIROFPROJECT . "/TestDataGenerationAlgorithm/PageTDG.php";
    require_once $BASEDIROFPROJECT . "/TestDataGenerationAlgorithm/configs/AVMConfig.php";
    require_once $BASEDIROFPROJECT . "/TestDataGenerationAlgorithm/Components/Solution/AvmSolution.php";

    abstract class AVM_PageTDG extends PageTDG
    {

        public function __construct()
        {
            $this->CurrentSolution = new AVM_Solution();
        }

        public function GenerateTestData()
        {
            $this->Result = new TDGResultForPageUnderTest();
            $this->RunPageUnderTest($this->GetNewRandomSolution());

            //  $time_start = microtime(true);//Start Time

            echo "</br>\n  Begining TDG for[F:" . $this->FilePath . ",I" . $this->CurrentSolution->GetStringOfInputsNameAndValues() . "]";

            //firstRun with Initial RandomValue
            $this->RunPageUnderTest($this->CurrentSolution);
            $this->RunPageUnderTest($this->GetNewRandomSolution());


            //LocalVariable
            $ReachedBranchList = null;
            $BestSolutionForBranchUnderTDG = null;
            $CSs = null;//ListOfCandidateSolution

            if (count($this->CurrentInterface) > 0)
                {

                $ArrayOfParamNames = array_keys($this->CurrentSolution->InputVector);

           
                $NumberOfInput = count($this->CurrentInterface);
                $CoverageImprove = true;
                while ($CoverageImprove)
                    {
                    $ReachedBranchList = $this->CoverageTable->GetAllReachedBranch($this->CurrentInterface, $this->BranchesID);

                    foreach ($ReachedBranchList as $TargetBranchObject)
                        {

                        $TargetedReachedBranch = (object)$TargetBranchObject;

                        $FitnessOfCurrentBranch = $TargetedReachedBranch->getFitness();

                        $NumberOfOptimizedVariable = 1;
                        $NumberOfGenerationForEachReachedBranch = 0;
                        $IndexOfSelectedInputForMutation = 0;
                        $BestSolutionForCurrentBranch = $this->CoverageTable->GetCopyOfBestSolutionForBranch($TargetedReachedBranch);

                        while ($NumberOfOptimizedVariable <= $NumberOfInput && $FitnessOfCurrentBranch > 0 && $NumberOfGenerationForEachReachedBranch < AVMConfig::$MaxGenerationNumber)
                            {
                            $SelectedInputNameForMutation = $ArrayOfParamNames[$IndexOfSelectedInputForMutation];
                            $SelectedInputForMutation = $this->InputsType[$SelectedInputNameForMutation];
                            $this->CurrentSolution = clone $BestSolutionForCurrentBranch;
                            $PreviousFitness = $this->CoverageTable->GetFitnessFor($TargetedReachedBranch);
                            if ($SelectedInputForMutation->InputType === VariableType::DOUBLE || $SelectedInputForMutation->InputType === VariableType::INTEGER || $SelectedInputForMutation->InputType === VariableType::FLOAT)
                                {
                                $PreviousFitness = $this->CoverageTable->GetFitnessFor($TargetedReachedBranch);
                                $dir = $this->ExploratoryMove($BestSolutionForCurrentBranch, $SelectedInputNameForMutation, $TargetedReachedBranch);

                                if ($dir != 0)
                                    {
                                    $CurrentDistance = $this->CoverageTable->GetFitnessFor($TargetedReachedBranch);

                                    //pattern move
                                    while ($CurrentDistance > 0 && $PreviousFitness > $CurrentDistance)
                                        {
                                        $MutationOprator = new AVMMutationOprator();
                                        $MutationOprator->dir = $dir;
                                        $MutationOprator->AmuntOfChange *= 2;

                                        $this->CurrentSolution = clone $BestSolutionForCurrentBranch;
                                        $this->PatternMove($BestSolutionForCurrentBranch, $MutationOprator, $CurrentDistance, $PreviousFitness, $IndexOfSelectedInputForMutation);

                                        $PreviousFitness = $CurrentDistance;
                                        if ((microtime(true) - $this->time_start) > CommonConfig::$MaxExecutionTime)
                                            break;
                                        $CurrentDistance = $this->RunPageUnderTestAndGetFitness($this->CurrentSolution, $TargetedReachedBranch);
                                        }

                                    $IndexOfSelectedInputForMutation++;
                                    if ($IndexOfSelectedInputForMutation >= $NumberOfInput)
                                        {
                                        $IndexOfSelectedInputForMutation = 0;
                                        }
                                    } else
                                    {
                                    $IndexOfSelectedInputForMutation++;
                                    if ($IndexOfSelectedInputForMutation >= $NumberOfInput)
                                        {
                                        $IndexOfSelectedInputForMutation = 0;
                                        }
                                    $this->CurrentSolution = clone $BestSolutionForCurrentBranch;
                                    }
                                } else
                                {
                                $newValue = array();
                                if ($SelectedInputForMutation->InputType === VariableType::STRING)
                                    {

                                    $newValue = $SelectedInputForMutation->GetNeighboursOfThisWithSeededAndRandomValue(PSOConfig::$NumberOfRandomString);

                                    } else if ($SelectedInputForMutation->InputType === VariableType::_ARRAY)
                                    {
                                    $Input = $this->InputsType[$SelectedInputNameForMutation];
                                    $newValue = array();
                                    for ($i = 0; $i < AVMConfig::$PopulationSizeForArrayInput; $i++)
                                        {
                                        $newValue[] = $Input->GetRandomValue();
                                        }
                                    } else//CustomInput
                                    {
                                    $Input = $this->InputsType[$SelectedInputNameForMutation];
                                    $newValue = array();
                                    for ($i = 0; $i < $Input->NumberInEachGeneration; $i++)
                                        {
                                        $newValue[] = $Input->GetRandomValue();
                                        }
                                    }
                                $NewSolutions = array();
                                $NewSolutions = $this->CurrentSolution->GetNewSolutionsWithValueFor($SelectedInputNameForMutation, $newValue);
                                foreach ($NewSolutions as $NewSolution)
                                    {
                                    if ((microtime(true) - $this->time_start) > CommonConfig::$MaxExecutionTime)
                                        break;
                                    $this->RunPageUnderTestAndGetFitness($NewSolution, $TargetedReachedBranch);
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
                            $NumberOfGenerationForEachReachedBranch++;
                            }


                        $this->Result->NumberOfGeneration += $NumberOfGenerationForEachReachedBranch;
						if ($this->CoverageTable->IsPageUnderTestFullCovered($this->BranchesID))
                        {
                        break;
                        }
                        }
                    $CoverageImprove = $this->CoverageTable->CheckForCoverageImproveAndUpdateLastFitForPageUnderTest($this->BranchesID);
                    }
                }

            //  $this->Result->ExecutionTime= (microtime(true) - $time_start);//Execution Time
            //   $this->Result->PercentageOfCoverage=$this->CoverageTable->GetPercentageOfCoverageForPageUnderTest($this->BranchesID);
            //    $this->Result->Interface=$this->CurrentInterface;
            //    $this->Result->PageUnderTestPath=$this->FilePath;
            //    $this->Result->RowState=$this->CoverageTable->GetRowStateForPageUnderTest($this->BranchesID);
        }


        public function PatternMove(&$BestSolution, & $MOP, $CurrentDistance, $PreviousDistance, $IndexOfSelectedInputForMutation)
        {
            if ($CurrentDistance > $PreviousDistance)
                {
                $MOP->AmuntOfChange = 1;
                $this->CurrentSolution = Clone $BestSolution;
                } else if ($CurrentDistance < $PreviousDistance)
                {
                $MOP->AmuntOfChange = $MOP->AmuntOfChange * 2;
                $BestSolution = clone $this->CurrentSolution;
                }

            $ArrayOfParamNames = array_keys($this->CurrentSolution->InputVector);
            $InputName = $ArrayOfParamNames[$IndexOfSelectedInputForMutation];

            $temp = (object)$this->InputsType[$InputName];
            if ($temp->InputType === VariableType::INTEGER)
                {
                $this->CurrentSolution->SetInputValue($InputName, $this->CurrentSolution->GetInputValue($InputName) + ($MOP->AmuntOfChange * $MOP->dir));
                $temp->SetCurrentValue($temp->GetCurrentValue());

                } else if ($temp->InputType === VariableType::DOUBLE || $temp->InputType === VariableType::FLOAT)
                {
                $this->CurrentSolution->SetInputValue($InputName, $this->CurrentSolution->GetInputValue($InputName) + ($MOP->AmuntOfChange * $MOP->dir * pow(10, -$temp->NumberOfPrecision)));
                $temp->SetCurrentValue($temp->GetCurrentValue() + ($MOP->AmuntOfChange * $MOP->dir * pow(10, -$temp->NumberOfPrecision)));
                }
        }

        public function ExploratoryMove(&$Solution, $NameOfSelectedInputForMutation, EBLE $TargetBranch)
        {
            $result = 0;
            $S1 = clone $Solution;//plus selected input
            $PreviousDistance = $TargetBranch->getFitness();

            $S1->SetInputValue($NameOfSelectedInputForMutation, $S1->GetInputValue() + 1);
            $CurrentDistance = $this->RunPageUnderTestAndGetFitness($S1, $TargetBranch);
            if ($CurrentDistance < $PreviousDistance)
                {
                $result = +1;
                $Solution = $S1;
                } else
                {
                $S2 = clone $Solution;//mines Selected input
                $S1->SetInputValue($NameOfSelectedInputForMutation, $S1->GetInputValue() - 1);
                $CurrentDistance = $this->RunPageUnderTestAndGetFitness($S1, $TargetBranch);
                if ($CurrentDistance < $PreviousDistance)
                    {
                    $result = -1;
                    $Solution = $S2;
                    }
                }
            return $result;
        }


        function destroy()
        {
        }

        private function GetNewRandomSolution()
        {
            $NewSolution = clone $this->CurrentSolution;

            foreach ($this->CurrentInterface as $ParamName => $ParamRequestType)
                {
                $Input = (object)$this->InputsType[$ParamName];
                $NewSolution->SetInputValue($ParamName, $Input->GetRandomValue());
                }
            return $NewSolution;
        }
    }
