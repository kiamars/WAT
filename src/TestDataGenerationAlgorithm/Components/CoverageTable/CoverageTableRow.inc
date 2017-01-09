<?php
class CoverageTableRow
{
    public $BranchId = 0;
    public $BranchSection = "T";
    public $BranchFitness = INF;
    public $LastFitness = INF;

    public $BestSolution = null;
    public $state=null;
    public $IsCovered = false;

    public $IsReachedAndFitnessIsImproved=false;

    function __construct($branchId, $branchSection, IGetSolution $SolutionGetter=null, $branchFitness = INF, $State=null)
    {
        $this->BranchId = $branchId;
        $this->BranchSection = $branchSection;
        $this->state = $State;
        $this->BranchFitness = $branchFitness;
        if($SolutionGetter!==null)
            $this->BestSolution = $SolutionGetter->GetSolution();
    }

    public function ResetSpecificValueInRow()
    {
        $this->BranchFitness = INF;
        $this->LastFitness = INF;
        $this->BestSolution = null;
        $this->IsCovered = false;
        $this->state=null;
    }

    public function GetBestSolutionInterface()
    {
        return $this->BestSolution->Interface;
    }
    public function GetStringOfBestSolutionInterface()
    {
        $r="";
        foreach($this->GetBestSolutionInterface() as $ParName=>$RequestType)
            $r.=",".$ParName.":".$RequestType;
        return $r;
    }

    public function getBranchId()
    {
        return $this->BranchId;
    }
    public function setBranchId($BranchId)
    {
        $this->BranchId = $BranchId;
    }
    public function getBranchFitness()
    {
        return $this->BranchFitness;
    }
    public function setBranchFitness($BranchFitness)
    {
        $this->BranchFitness = $BranchFitness;
    }
    public function getBestSolution()
    {
        return $this->BestSolution;
    }
    public function setBestSolution(IGetSolution $GetBestSolution)
    {
        $this->BestSolution = $GetBestSolution->GetSolution();
    }
    public function getBranchSection()
    {
        return $this->BranchSection;
    }
    public function setBranchSection($BranchSection)
    {
        $this->BranchSection = $BranchSection;
    }
    public function isIsCovered()
    {
        return $this->IsCovered;
    }
    public function setIsCovered($IsCovered)
    {
        $this->IsCovered = $IsCovered;
    }
    public function getLastFitness()
    {
        return $this->LastFitness;
    }
    public function setLastFitness($LastFitness)
    {
        $this->LastFitness = $LastFitness;
    }

    public  function GetString()
    {
        $result="";

        $result.= "\n BId:".$this->BranchId;
        $result.=  "| BSection:".$this->BranchSection;
        $result.=  "| BestFit:".$this->BranchFitness;
        $result.=  "| LastFit:".$this->BranchFitness;
        if(isset($this->BestSolution))
            $result.= "| BestSolution:". $this->BestSolution->GetString();
        $result.=  "| IsCovered:".$this->IsCovered;

        if($this->state)
            $result.=  "| State:".$this->state->GetString();
        return $result;
    }
    function destroy()
    {
        $this->BestSolution=null;
        $this->state=null;
    }

/*
    public  function ToString()
    {
        $ret="";
        $ret.="ID:".$this->BranchId;
        $ret.=" Sec:".$this->BranchSection;
        $ret.=" Fit:".$this->getBranchFitness();
        $ret.="LFit".$this->LastFitness;
        $ret.="Solution".$this->BestSolution->ToString();
        $ret.="State".$this->state->ToString();
        return $ret;
    }
    */
}