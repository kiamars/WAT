<?php
    /**
     * Created by PhpStorm.
     * User: Mirzaee
     * Date: 11/2/2016
     * Time: 9:10 PM
     */

    require_once "BaseDirectory.php";
    require_once $BASEDIROFPROJECT . "/TestDataGenerationAlgorithm/PageTDG.php";
    require_once $BASEDIROFPROJECT . "/TestDataGenerationAlgorithm/Components/Solution/ACO_Solution.php";
    require_once $BASEDIROFPROJECT . "/TestDataGenerationAlgorithm/configs/ACOConfig.php";

    abstract class ACO_PageTDG extends PageTDG
    {
        Public $RMax;
        public $BestAnt = null;
        public $Colony = array();

        public function __construct()
        {
            $this->CurrentSolution = new ACO_Solution();
        }

        public function GenerateTestData()
        {

            $this->Result = new TDGResultForPageUnderTest();

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
                $this->SetNumberOfPopulation();
                $NumberOfRun = 1;

                $CoverageImprove = true;
                while ($CoverageImprove)
                    {
                    $ReachedBranchList = $this->CoverageTable->GetAllReachedBranch($this->CurrentInterface, $this->BranchesID);

                    foreach ($ReachedBranchList as $TargetBranchObject)
                        {

                        $TargetedReachedBranch = (object)$TargetBranchObject;

                        $FitnessOfTargetBranch = $TargetedReachedBranch->getFitness();


                        //initialize colony:
                        $this->Colony = $this->GenerateColony($this->CurrentSolution);
                        $NumberOfGenerationForEachReachedBranch = 0;
                        while ($FitnessOfTargetBranch > 0 && $NumberOfGenerationForEachReachedBranch < ACOConfig::$MaxGenerationNumber)
                            {
                            //Calculate fitness
                            foreach ($this->Colony as $item)
                                {
                                if ((microtime(true) - $this->time_start) > CommonConfig::$MaxExecutionTime)
                                    break;
                                $item->fitness = $this->RunPageUnderTestAndGetFitness($item, $TargetedReachedBranch);
                                }

                            //LocalSearch
                            foreach ($this->Colony as &$AntXk)
                                {
                                //Do local search and Update AntXk If Lazemast
                                $AntXk = $this->LocalSearch($AntXk, $TargetedReachedBranch);
                                }

                            //GlobalSearch
                            $this->GlobalSearch($TargetedReachedBranch);

                            //UpdatePheromone
                            $this->UpdatePheremone();

                            $FitnessOfTargetBranch = $this->CoverageTable->GetFitnessFor($TargetedReachedBranch);

                            $NumberOfGenerationForEachReachedBranch = $NumberOfGenerationForEachReachedBranch + 1;

                            }
                        $this->Result->NumberOfGeneration += $NumberOfGenerationForEachReachedBranch;
                        $NumberOfRun = $NumberOfRun + $this->Result->NumberOfRun;
                        }
					
     //all branch is Reached Breack
                        if ($this->CoverageTable->IsPageUnderTestFullCovered($this->BranchesID))
                            {
                            break;
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

        public function GenerateColony(ACO_Solution $Solution)
        {
            foreach ($this->CurrentInterface as $ParamName => $ParamRequestType)
                {

                for ($i = 0; $i < ACOConfig::$ColonySize; $i++)
                    {
                    $Solution->Record[] = null;
                    }
                }

            $this->Colony = array();
            foreach ($this->CurrentInterface as $ParamName => $ParamRequestType)
                {
                $Input = (object)$this->InputsType[$ParamName];
                if ($Input->getInputType() === VariableType::DOUBLE || $Input->getInputType() === VariableType::INTEGER)
                    {
                        {
                        $newValue = array();
                        for ($i = 0; $i < ACOConfig::$PopulationSizeForNumericInput; $i++)
                            {
                            $newValue[] = $Input->GetRandomValue();
                            }
                        }
                    $this->Colony = array_merge($this->Colony, $this->CurrentSolution->GetNewSolutionsWithValueFor($ParamName, $newValue, true));
                    } else if ($Input->getInputType() === VariableType::STRING)
                    {
                    $newValue = $Input->GetNeighboursOfThisWithSeededAndRandomValue(ACOConfig::$NumberOfRandomString);
                    $this->Colony = array_merge($this->Colony, $this->CurrentSolution->GetNewSolutionsWithValueFor($ParamName, $newValue));
                    } elseif ($Input->getInputType() === VariableType::_ARRAY)
                    {
                    $newValue = array();
                    for ($i = 0; $i < ACOConfig::$PopulationSizeForArrayInput; $i++)
                        {
                        $newValue[] = $Input->GetRandomValue();
                        }

                    $this->Colony = array_merge($this->Colony, $this->CurrentSolution->GetNewSolutionsWithValueFor($ParamName, $newValue));
                    } else//Custom Input
                    {
                    $newValue = array();
                    for ($i = 0; $i < $Input->NumberInEachGeneration; $i++)
                        {
                        $newValue[] = $Input->GetRandomValue();
                        }
                    $this->Colony = array_merge($this->Colony, $this->CurrentSolution->GetNewSolutionsWithValueFor($ParamName, $newValue));
                    }
                }
        }


        private function GlobalSearch($TargetBranch)
        {
            //Favg
            $Fitness = array();
            for ($k = 0; count($this->Colony); $k++)
                {
                $Antk = (object)$this->Colony[$k];
                $Fitness[] = $Antk->fitness;
                }

            for ($k = 0; count($Fitness); $k++)
                {
                if ($Fitness[$k] <= 0)
                    $Fitness[$k] = 1;
                else if ($Fitness[$k] > 0 && $Fitness[$k] < 1)
                    $Fitness[$k] = 1 - $Fitness[$k];
                else
                    $Fitness[$k] = 1 / $Fitness[$k];
                }

            $SumFitness = 0;
            for ($k = 0; count($Fitness); $k++)
                {
                $SumFitness = $Fitness[$k];
                }
            $FAvg = $SumFitness / count($Fitness);


            $PKJ = array();
            $Antk = null;
            $tempAnt = null;
            for ($k = 0; count($this->Colony); $k++)
                {
                $Antk = (object)$this->Colony[$k];
                if ($Fitness[$k] < $FAvg && (mt_rand() / mt_getrandmax()) < ACOConfig::$q0)
                    {
                    $tempAnt = $this->GetNewRandomSolutionInBoundedArea($Antk, PHP_INT_MAX);
                    if ((microtime(true) - $this->time_start) > CommonConfig::$MaxExecutionTime)
                        break;
                    $tempAnt->fitness = $this->RunPageUnderTestAndGetFitness($tempAnt, $TargetBranch);
                    $this->Colony[$k] = $tempAnt;
                    //Must change
                    } else
                    {
                    //Calculate PkJ
                    for ($j = 0; $j < count($this->Colony); $j++)
                        {
                        $PKJ[] = 0;//$PKJ[j]=
                        $Dkj = $Fitness[k] - $Fitness[j];
                        if ($Dkj < 0)
                            {
                            $SumOfPheromone = 0;
                            for ($u = 0; $u < count($this->Colony); $u++)
                                {
                                $tempAnt = (object)$this->Colony[$u];
                                $Dku = $Fitness[k] - $Fitness[u];
                                $SumOfPheromone += $tempAnt->Pheromone * (exp(-$Dku) / ACOConfig::$T);
                                }

                            $tempAnt = (object)$this->Colony[$j];
                            $PKJ[$j] = $tempAnt->Pheromone * (exp(-$Dkj) / ACOConfig::$T) / $SumOfPheromone;
                            }

                        }

                    $ret = 1;
                    $SumOfWeight = 0;
                    $weightI = array();
                    $weightV = array();
                    for ($j = 0; $j < count($PKJ); $j++)
                        {
                        if ($PKJ[$j] != 0)
                            {
                            $weightI[] = $j;
                            $weightV[] = $PKJ[$j];
                            $SumOfWeight += $PKJ[$j];
                            }
                        }

                    $slice = 0;
                    $loop = 1;
                    $CurrentFit = 0;
                    while ($loop != 0)
                        {
                        $slice = mt_rand() / mt_getrandmax() * $SumOfPheromone;
                        $CurrentFit = 0;
                        for ($index = 0; $index < count($weightV); $index++)
                            {
                            $CurrentFit = $CurrentFit + $weightV[$index];
                            if ($CurrentFit >= $slice)
                                {
                                $ret = $weightI[$index];
                                $loop = 0;
                                break;
                                }
                            }
                        }

                    $indexOfSelectedAsNext = $ret;
                    $tempAnt = (object)$this->Colony[$indexOfSelectedAsNext];
                    $tempAnt->Count++;
                    $tempAnt->Record[$tempAnt->Count] = $this->Colony[$k];

                    if ((mt_rand() / mt_getrandmax()) < ACOConfig::$p0)
                        {
                        $tempAnt = (object)$this->Colony[$k];
                        $tempAnt = $tempAnt->RandomSolutionInBoundedArea($this->RMax);
                        $tempAnt->fitness = $this->RunPageUnderTestAndGetFitness($tempAnt, $TargetBranch);
                        $this->Colony[$k] = $tempAnt;
                        //Must change
                        } else
                        {
                        $AntK = (object)$this->Colony[$k];
                        $Antj = (object)$this->Colony[$indexOfSelectedAsNext];

                        foreach ($this->CurrentInterface as $ParamName => $ParamType)
                            {
                            $Input = $this->InputsType[$ParamName];

                            if ($Input->getInputType() === VariableType::DOUBLE || $Input->getInputType() === VariableType::INTEGER)
                                {
                                $Xk = $AntK->GetInputValue($ParamName);
                                $XJj = $Antj->GetInputValue($ParamName);
                                $AntK->SetInputValue($ParamName, (ACOConfig::$Phi * $XJj) + ((1 - ACOConfig::$Phi) * $Xk));
                                } else if ($Input->getInputType() === VariableType::STRING)
                                {
                                $AntK->SetInputValue($ParamName, $Input->GetRandomValue());
                                } else if ($Input->getInputType() === VariableType::_ARRAY)
                                {
                                $AntK->SetInputValue($ParamName, $Input->GetRandomValue());
                                } else//CustomInput
                                {
                                $AntK->SetInputValue($ParamName, $Input->GetRandomValue());
                                }
                            }

                        $AntK->fitness = $this->RunPageUnderTestAndGetFitness($AntK, $TargetBranch);
                        $this->Colony[$k] = $AntK;
                        //Must change
                        }
                    }
                }
        }

        /*
        * Return Best Ant In Neighbour
        */
        private function LocalSearch(ACO_Solution &$AntXk, $TargetBranch)
        {
            $TempAnt = $this->GetNewRandomSolutionInBoundedArea($AntXk, $this->RMax);

            $TempAnt->fitness = $this->RunPageUnderTestAndGetFitness($TempAnt, $TargetBranch);

            if ($TempAnt->fitness < $AntXk->fitness)
                {
                return $TempAnt;
                }
            return $AntXk;
        }


        private function UpdatePheremone()
        {
            $tempAnt = null;
            $tempAnt2 = null;
            for ($np = 0; $np < count($this->Colony); $np++)
                {
                $SumOfFit = 0;
                $tempAnt = (object)$this->Colony[$np];
                for ($i = 0; $i < $tempAnt->Count; $i++)
                    {
                    $tempAnt2 = (object)$tempAnt->Record[$i];

                    if ($tempAnt2->Fitness <= 0)
                        $SumOfFit = $SumOfFit + 1;
                    else if ($tempAnt2->Fitness > 0 && $tempAnt2->Fitness < 1)
                        $SumOfFit = $SumOfFit + (1 - $tempAnt2->Fitness);
                    else
                        $SumOfFit = $SumOfFit + 1 / $tempAnt2->Fitness;
                    }
                }
        }

        private function GetNewRandomSolutionInBoundedArea(ACO_Solution $solution, $Rmax)
        {
            $ReturnValue = clone $solution;

            for ($i = 0; $i < ACOConfig::$ColonySize; $i++)
                {
                $ReturnValue->Record[] = null;
                }

            foreach ($this->CurrentInterface as $ParamName => $ParamRequestType)
                {
                $Input = (object)$this->InputsType[$ParamName];
                if ($Input->getInputType() === VariableType::DOUBLE || $Input->getInputType() === VariableType::INTEGER)
                    {
                    $ReturnValue->SetInputValue($ParamName, $this->GetRandomBoundedValue($ParamName, $ReturnValue->GetInputValue($ParamName), $Rmax));
                    } else if ($Input->getInputType() === VariableType::STRING)
                    {
                    $ReturnValue->SetInputValue($ParamName, $Input->GetRandomValue());
                    } else if ($Input->getInputType() === VariableType::_ARRAY)
                    {
                    $ReturnValue->SetInputValue($ParamName, $Input->GetRandomValue());
                    } else//CustomInput
                    {
                    $ReturnValue->SetInputValue($ParamName, $Input->GetRandomValue());
                    }
                }
            return $ReturnValue;
        }

        private function GetRandomBoundedValue($ParamName, $ParamValue, $RMax)
        {
            $Input = $this->InputsType[$ParamName];
            $R = $Input->GetRandomValue();
            if ($ParamValue + $RMax < $R)
                $R = $ParamValue + $RMax;
            else if ($R < $ParamValue + $RMax)
                $R = $ParamValue + $RMax;
            return $R;
        }

        private function SetNumberOfPopulation()
        {

            $popSize = 0;
            foreach ($this->CurrentInterface as $ParamName => $ParamRequestType)
                {
                $Input = (object)$this->InputsType[$ParamName];
                if ($Input->getInputType() === VariableType::DOUBLE || $Input->getInputType() === VariableType::INTEGER)
                    {
                    $popSize += ACOConfig::$PopulationSizeForNumericInput;
                    } else if ($Input->getInputType() === VariableType::STRING)
                    {
                    $popSize += ACOConfig::$NumberOfRandomString;
                    } else if ($Input->getInputType() === VariableType::_ARRAY)
                    {
                    $popSize += ACOConfig::$PopulationSizeForArrayInput;
                    } else//CustomInput
                    {
                    $popSize += $Input->NumberInEachGeneration;
                    }
                }
            ACOConfig::$ColonySize = $popSize;
        }

        public function GetNewRandomSolution()
        {
            $NewSolution = clone $this->CurrentSolution;
            for ($i = 0; $i < ACOConfig::$ColonySize; $i++)
                {
                $NewSolution->Record[] = null;
                }
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
