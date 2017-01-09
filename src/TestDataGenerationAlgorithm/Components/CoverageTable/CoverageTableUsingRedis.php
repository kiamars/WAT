<?php
/**
 * Created by PhpStorm.
 * User: computer
 * Date: 11/11/2016
 * Time: 10:12 PM
 */

require_once "BaseDirectory.php";
require_once $BASEDIROFPROJECT . "/ExternalLibs/vendor/autoload.php";
require_once "CoverageTableRow.inc";

class CoverageTableRedisVersion
{
    public $Rows = array();
    public $RedisClient = null;
    public $NumberOfAllBranchesInProgram = 0;

    public function __construct($NumberOfBranchesInAllPages)
    {
        $this->Rows=array();
        $this->NumberOfAllBranchesInProgram = $NumberOfBranchesInAllPages;
        $this->RedisClient = new Predis\Client();
        $e=null;
        $this->RedisClient->executeRaw(array("FLUSHALL"),$e);;
    }

    public function GetCoverageTable()
    {
        $Rows = array();
        foreach ($this->Rows as $RowID) {
            if (array_key_exists($RowID, $this->Rows)) {
                $Rows[$RowID] = $this->GetRow($RowID);
            }
        }
        return $Rows;
    }


    protected function SetRow($RowId, CoverageTableRow $Row)
    {
        $this->Rows[$RowId] = $RowId;
        $this->RedisClient->set($RowId, serialize($Row));
    }

    protected function GetRow($RowID)
    {
        return unserialize($this->RedisClient->get($RowID));
    }

    public function CheckForCoverageImproveAndUpdateLastFitForPageUnderTest($PageUnderTestBranchesID)
    {
        $Result = false;
        foreach ($PageUnderTestBranchesID as $RowID) {
            if (array_key_exists($RowID, $this->Rows)) {
                $CoverageTableRow = $this->GetRow($RowID);
                if ($CoverageTableRow->getBranchFitness() < $CoverageTableRow->getLastFitness() && $CoverageTableRow->getBranchFitness() > 0) {
                    $Result = true;
                }
                $CoverageTableRow->setLastFitness($CoverageTableRow->getBranchFitness());

                $this->SetRow($RowID, $CoverageTableRow);
            }
            //  echo "[LF:".$CoverageTableRow->getLastFitness()." Fit:".$CoverageTableRow->getBranchFitness()."]";
        }
        return $Result;
    }

    /**
     * @param $ListOfExecutedBranchList :array of VsEbl
     * @param $Solution
     * @param $state : the state in that this branch is reached
     */
    public function UpdateInputDataAndFitnessForBranchForPageUnderTest($ListOfExecutedBranchList, $Solution, $state)
    {
        foreach ($ListOfExecutedBranchList as $ExecutedBranchObject) {
            $ExecutedBranch = (object)$ExecutedBranchObject;

            if (!array_key_exists($ExecutedBranch->getBranchId(), $this->Rows)) {
                //Add to coverage table
                $CoverageTableRow = new CoverageTableRow($ExecutedBranch->getBranchId(), $ExecutedBranch->getBranchSection(), $Solution, $ExecutedBranch->getFitness(), $state);
                if ($CoverageTableRow->getBranchFitness() <= 0) {
                    $CoverageTableRow->setIsCovered(true);
                    $CoverageTableRow->setBranchFitness(0);
                }

                $CoverageTableRow->IsReachedAndFitnessIsImproved = true;
                //in initial state, lastFit ANd fit is equal2
                $CoverageTableRow->setLastFitness($CoverageTableRow->getBranchFitness());
                $this->SetRow($ExecutedBranch->getBranchId(), $CoverageTableRow);
                $this->Rows[$ExecutedBranch->getBranchId()] = $ExecutedBranch->getBranchId();
            } else {
                //if this row is exist
                $CoverageTableRow = $this->GetRow($ExecutedBranch->getBranchId());
                if ($CoverageTableRow->getBranchId() == $ExecutedBranch->getBranchId() && $CoverageTableRow->getBranchSection() == $ExecutedBranch->getBranchSection()) {
                    if ($CoverageTableRow->getBranchFitness() >= $ExecutedBranch->getFitness()) {
                        $CoverageTableRow->setBranchFitness($ExecutedBranch->getFitness());
                        $CoverageTableRow->setBestSolution($Solution);
                        $CoverageTableRow->state = $state;

                        if ($CoverageTableRow->getBranchFitness() < $CoverageTableRow->getLastFitness()) {
                            $CoverageTableRow->IsReachedAndFitnessIsImproved = true;
                        }

                        if ($CoverageTableRow->getBranchFitness() <= 0) {
                            $CoverageTableRow->setIsCovered(true);
                            $CoverageTableRow->setBranchFitness(0);
                        }
                    }
                }

                $this->SetRow($ExecutedBranch->getBranchId(), $CoverageTableRow);
                $this->Rows[$ExecutedBranch->getBranchId()] = $ExecutedBranch->getBranchId();
            }
        }
    }


    /**
     * get reached and Improved but not covered Branches
     * @param $CurrentInterface
     * @param $PageUnderTestBranchesID
     * @return array
     */
    public function GetAllReachedBranch($CurrentInterface, $PageUnderTestBranchesID)
    {
        $Result = array();
        foreach ($PageUnderTestBranchesID as $RowID) {
            if (array_key_exists($RowID, $this->Rows)) {
                $CoverageTableRow = $this->GetRow($RowID);
                if (!empty($CoverageTableRow)) {
                    if ($CoverageTableRow->IsReachedAndFitnessIsImproved && !$CoverageTableRow->isIsCovered()) {
                        $BestSolutionInterFace = $CoverageTableRow->GetBestSolutionInterface();
                        if ($BestSolutionInterFace === $CurrentInterface) {
                            $Result[] = new EBLE($CoverageTableRow->getBranchId(), $CoverageTableRow->getBranchSection(), $CoverageTableRow->getBranchFitness());
                            $CoverageTableRow->IsReachedAndFitnessIsImproved = false;
                            $this->SetRow($RowID, $CoverageTableRow);
                        }
                    }
                }
            }
        }
        return $Result;
    }

    public function GetPercentageOfCoverageForPageUnderTest($PageUnderTestBranchesID)
    {
        $NumberOfAllBranch = 0;
        foreach ($PageUnderTestBranchesID as $RowID) {
            if (array_key_exists($RowID, $this->Rows)) {
                $CoverageTableRow = $this->GetRow($RowID);
                if (!empty($CoverageTableRow))
                    if ($CoverageTableRow->isIsCovered()) {
                        $NumberOfAllBranch++;
                    }
            }
        }
        return ($NumberOfAllBranch / (count($PageUnderTestBranchesID) + 0.0)) * 100;
    }

    public function IsPageUnderTestFullCovered($PageUnderTestBranchesID)
    {
        if ($this->GetPercentageOfCoverageForPageUnderTest($PageUnderTestBranchesID) === 100)
            return true;
        return false;
    }


    public function GetCopyOfBestSolutionForBranch(EBLE $ExecutedBranchListElement)
    {
        $ReturnResult = null;
        $CoverageTableRow = $this->GetRow($ExecutedBranchListElement->getBranchId());
        $ReturnResult = (clone $CoverageTableRow->getBestSolution());

        return $ReturnResult;
    }

    public function GetCopyOfStateForBranch(EBLE $ExecutedBranchListElement)
    {
        $ReturnResult = null;
        $CoverageTableRow = $this->GetRow($ExecutedBranchListElement->getBranchId());
        $ReturnResult = (clone $CoverageTableRow->state);

        return $ReturnResult;
    }


    public function GetFitnessFor(EBLE $ExecutedBranchListElement)
    {
        $FitnessOfBranch = null;
        $CoverageTableRow = $this->GetRow($ExecutedBranchListElement->getBranchId());
        $FitnessOfBranch = $CoverageTableRow->getBranchFitness();
        return $FitnessOfBranch;
    }


    public function IsFullCoverage()
    {
        $result = false;
        $NumberOfAllBranch = 0;

        $CoverageTableRow = null;
        foreach ($this->Rows as $RowID) {
            $CoverageTableRow = $this->GetRow($RowID);
            if ($CoverageTableRow->isIsCovered()) {
                $NumberOfAllBranch++;
            }
        }

        if ($NumberOfAllBranch === $this->NumberOfAllBranchesInProgram) {
            $result = true;
        }
        return $result;
    }

    public function GetPercentOfCoverage()
    {
        $NumberOfBranches = count($this->Rows);
        $NumberOfAllBranch = 0;
        $CoverageTableRow = null;
        foreach ($this->Rows as $RowID) {
            $CoverageTableRow = $this->GetRow($RowID);
            if ($CoverageTableRow->isIsCovered()) {
                $NumberOfAllBranch++;
            }
        }
        if ($this->NumberOfAllBranchesInProgram < $NumberOfBranches)
            echo "\n Number of reached branch in Coverage Table is  grater than Setted value in Coverage table \n";
        return ($NumberOfAllBranch / ($this->NumberOfAllBranchesInProgram + 0.0)) * 100;
    }

    public function CheckForCoverageImproveAndUpdateLastFit()
    {
        $Result = false;
        $CoverageTableRow = null;
        foreach ($this->Rows as $RowID) {
            $CoverageTableRow = $this->GetRow($RowID);
            if ($CoverageTableRow->getBranchFitness() < $CoverageTableRow->getLastFitness()) {
                $Result = true;
            }
            $CoverageTableRow->setLastFitness($CoverageTableRow->getBranchFitness());
            //  echo "[LF:".$CoverageTableRow->getLastFitness()." Fit:".$CoverageTableRow->getBranchFitness()."]";
            $this->SetRow($RowID, $CoverageTableRow);
        }
        return $Result;
    }

    public function GetRowStateForPageUnderTest($PageUnderTestBranchesID)
    {
        $result = "BranchIDInOrderThatAreAddedToCT:\n";
        foreach ($PageUnderTestBranchesID as $RowID) {
            if (array_key_exists($RowID, $this->Rows)) {
                $CoverageTableRow = (object)$this->Rows[$RowID];
                if (!empty($CoverageTableRow)) {

                    $result .= "[(" . mbsplit(",", $CoverageTableRow->getBranchId())[1] . ")=" . $CoverageTableRow->getBranchFitness() . "][InterFace" . $CoverageTableRow->GetStringOfBestSolutionInterface() . "] \n";
                }
            }
        }
        return $result;
    }

    public function GetNumberOfCoveredBranches()
    {


        $NumberOfAllBranch = 0;
        $CoverageTableRow = null;
        foreach ($this->Rows as $RowID) {
            $CoverageTableRow = $this->GetRow($RowID);
            if ($CoverageTableRow->isIsCovered()) {
                $NumberOfAllBranch++;
            }
        }
        return $NumberOfAllBranch;
    }

    function destroy()
    {
        $this->Rows = array();
        $this->RedisClient = null;
    }
}