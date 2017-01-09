<?php

require_once "CoverageTableRow.inc";
class CoverageTable
{
    public  $Rows=array();
    public $NumberOfAllBranchesInProgram=0;

    public function __construct($NumberOfBranchesInAllPages)
    {
        $this->NumberOfAllBranchesInProgram=$NumberOfBranchesInAllPages;
    }



    public function CheckForCoverageImproveAndUpdateLastFitForPageUnderTest($PageUnderTestBranchesID)
    {
        $Result=false;
        foreach($PageUnderTestBranchesID as $RowID)
        {
            if (array_key_exists($RowID, $this->Rows))
            {
                $CoverageTableRow=(object)$this->Rows[$RowID];
                if($CoverageTableRow->getBranchFitness() < $CoverageTableRow->getLastFitness() && $CoverageTableRow->getBranchFitness()>0)
                {
                    $Result=true;
                }
            }
            $CoverageTableRow->setLastFitness($CoverageTableRow->getBranchFitness());
            //  echo "[LF:".$CoverageTableRow->getLastFitness()." Fit:".$CoverageTableRow->getBranchFitness()."]";
        }
        return $Result;
    }

    /**
     * @param $ListOfExecutedBranchList:array of VsEbl
     * @param $Solution
     * @param $state: the state in that this branch is reached
     */
    public function UpdateInputDataAndFitnessForBranchForPageUnderTest($ListOfExecutedBranchList,$Solution,$state)
    {
        foreach ( $ListOfExecutedBranchList as $ExecutedBranchObject ) {
            $ExecutedBranch=(object)$ExecutedBranchObject;

            if(!array_key_exists($ExecutedBranch->getBranchId(),$this->Rows))
            {
            //Add to coverage table
                $CoverageTableRow= new CoverageTableRow($ExecutedBranch->getBranchId(),$ExecutedBranch->getBranchSection(),$Solution, $ExecutedBranch->getFitness(), $state);
                if($CoverageTableRow->getBranchFitness()<=0)
                {
                    $CoverageTableRow->setIsCovered(true);
                    $CoverageTableRow->setBranchFitness(0);
                }
                //in first lastFit ANd fit is equal2
                $CoverageTableRow->setLastFitness($CoverageTableRow->getBranchFitness());
                $this->Rows[$ExecutedBranch->getBranchId()]=$CoverageTableRow;
            }
            else
            {
                //if this row is exist
                $CoverageTableRow=(object)$this->Rows[$ExecutedBranch->getBranchId()];
                if($CoverageTableRow->getBranchId() ==$ExecutedBranch->getBranchId() && $CoverageTableRow->getBranchSection()==$ExecutedBranch->getBranchSection())
                {
                    if($CoverageTableRow->getBranchFitness() >= $ExecutedBranch->getFitness())
                    {
                        $CoverageTableRow->setBranchFitness($ExecutedBranch->getFitness());
                        $CoverageTableRow->setBestSolution($Solution);
                        $CoverageTableRow->state=$state;

                        if($CoverageTableRow->getBranchFitness()<=0)
                        {
                            $CoverageTableRow->setIsCovered(true);
                            $CoverageTableRow->setBranchFitness(0);
                        }
                    }
                }
            }
        }
    }


    /**
     * @param $CurrentInterface
     * @param $PageUnderTestBranchesID
     * @return array
     */
    public function GetAllReachedBranch($CurrentInterface,$PageUnderTestBranchesID)
    {
        $Result=array();
        foreach($PageUnderTestBranchesID as $RowID)
        {
            if (array_key_exists($RowID, $this->Rows))
            {
                $CoverageTableRow=(object)$this->Rows[$RowID];
                if (!empty($CoverageTableRow))
                {
                    if(!is_infinite($CoverageTableRow->getBranchFitness()) && !$CoverageTableRow->isIsCovered())
                    {
                        $BestSolutionInterFace =$CoverageTableRow->GetBestSolutionInterface();
                        if($BestSolutionInterFace===$CurrentInterface)
                            $Result[]=new EBLE($CoverageTableRow->getBranchId(),$CoverageTableRow->getBranchSection(),$CoverageTableRow->getBranchFitness()) ;
                    }
                }
            }
        }
        return $Result;
    }

    public function GetPercentageOfCoverageForPageUnderTest($PageUnderTestBranchesID)
    {
        $NumberOfAllBranch=0;
        foreach($PageUnderTestBranchesID as $RowID)
        {
            if (array_key_exists($RowID, $this->Rows))
            {
                $CoverageTableRow=(object)$this->Rows[$RowID];
                if (!empty($CoverageTableRow))
                    if($CoverageTableRow->isIsCovered())
                    {
                        $NumberOfAllBranch++;
                    }
            }
        }
        return ($NumberOfAllBranch/(count($PageUnderTestBranchesID)+0.0))*100;
    }
    public function IsPageUnderTestFullCovered($PageUnderTestBranchesID)
    {
        if($this->GetPercentageOfCoverageForPageUnderTest($PageUnderTestBranchesID)===100)
            return true;
        return false;
    }

    public function GetRowStateForPageUnderTest($PageUnderTestBranchesID)
    {
        $result="BranchIDInOrderThatAreAddedToCT:\n";
        foreach($PageUnderTestBranchesID as $RowID) {
            if (array_key_exists($RowID, $this->Rows))
            {
                $CoverageTableRow = (object)$this->Rows[$RowID];
                if (!empty($CoverageTableRow)) {

                    $result.="[(".mbsplit(",",$CoverageTableRow->getBranchId())[1].")=".$CoverageTableRow->getBranchFitness()."][InterFace".$CoverageTableRow->GetStringOfBestSolutionInterface()."] \n" ;
                }
            }
        }
        return $result;
    }

    public function GetCopyOfBestSolutionForBranch(EBLE $ExecutedBranchListElement)
    {
        $ReturnResult=null;
        $CoverageTableRow=(object)$this->Rows[$ExecutedBranchListElement->getBranchId()];
        $ReturnResult=(clone $CoverageTableRow->getBestSolution());

        return $ReturnResult;
    }

    public function GetCopyOfStateForBranch(EBLE $ExecutedBranchListElement)
    {
        $ReturnResult=null;
        $CoverageTableRow=(object)$this->Rows[$ExecutedBranchListElement->getBranchId()];
        $ReturnResult=(clone $CoverageTableRow->state);

        return $ReturnResult;
    }


    public function GetFitnessFor(EBLE $ExecutedBranchListElement)
    {
        $FitnessOfBranch=null;
        $CoverageTableRow=(object)$this->Rows[$ExecutedBranchListElement->getBranchId()];
        $FitnessOfBranch=$CoverageTableRow->getBranchFitness();
        return $FitnessOfBranch;
    }


    public function RestSpecificValueInCoverageTable()
    {
         foreach($this->Rows as $CoverageTableRowObject ) {
             $CoverageTableRow=(object)$CoverageTableRowObject;
             $CoverageTableRow->ResetSpecificValueInRow();
         }
    }



    public function IsFullCoverage()
    {
        $result=false;
        $NumberOfBranches=count($this->Rows);
        $NumberOfAllBranch=0;
        foreach($this->Rows as $CoverageTableRowObject ) {
            $CoverageTableRow=(object)$CoverageTableRowObject;
            if($CoverageTableRow->isIsCovered())
            {
                $NumberOfAllBranch++;
            }
        }

        if($NumberOfAllBranch===$this->NumberOfAllBranchesInProgram)
        {
            $result=true;
        }
        return $result;
    }

    public function GetPercentOfCoverage()
    {
        $NumberOfBranches=count($this->Rows);
        $NumberOfAllBranch=0;
        foreach($this->Rows as $CoverageTableRowObject ) {
            $CoverageTableRow=(object)$CoverageTableRowObject;
            if($CoverageTableRow->isIsCovered())
            {
                $NumberOfAllBranch++;
            }
        }
        if( $this->NumberOfAllBranchesInProgram<$NumberOfBranches)
            echo "\n Number of reached branch in Coverage Table is  grater than Setted value in Coverage table \n";
        return ($NumberOfAllBranch/($this->NumberOfAllBranchesInProgram+0.0))*100;
    }

    public function CheckForCoverageImproveAndUpdateLastFit()
    {
        $Result=false;
        foreach($this->Rows as $CoverageTableRowObject ) {
            $CoverageTableRow=(object)$CoverageTableRowObject;
            if($CoverageTableRow->getBranchFitness() < $CoverageTableRow->getLastFitness())
            {
                $Result=true;
            }
            $CoverageTableRow->setLastFitness($CoverageTableRow->getBranchFitness());
            //  echo "[LF:".$CoverageTableRow->getLastFitness()." Fit:".$CoverageTableRow->getBranchFitness()."]";
        }
        return $Result;
    }





    public function GetString()
    {
        $result="";
        $temp=null;

        for($i=0;$i<count($this->Rows);$i++)
        {
            $temp=(object)$this->Rows[$i];
            $result.="\n ".($i+1).$temp->GetString();
        }
        $result.="PercentageOfCoverage".$this->GetPercentOfCoverage();
        return $result;
    }

    public  function PrintRowsState()
    {
        $result="BranchIDInOrderThatAreAddedToCT:\n";
        for($i=0;$i<count($this->Rows);$i++)
        {
            $temp=(object)$this->Rows[$i];
            $result.="[(".mbsplit(",",$temp->getBranchId())[1].")=".$temp->getBranchFitness()."][InterFace".$temp->GetStringOfBestSolutionInterface()."] \n" ;
        }
        return $result;
    }
    function destroy()
    {
        $this->Rows=array();
    }
}


/*
$q=array("a"=>"GET","b"=>"POST","c"=>"Cookies");

class a
{
    public  $p=array();
}
$o=new a();
$o->p=$q;
$q["s"]="asdc";
print_r($o->p);//
print_r($q);//
if($o->p===$q)
{
    echo "okey";
}
else{
    echo "Nokey";
}
*/