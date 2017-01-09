<?php
//executed branch list element
class EBLE
{
    protected $BranchId=0;
    /**
     * @var string T:true OR F:false
     */
    protected $BranchSection="";
    protected $Fitness=PHP_INT_MAX;

    /**
     * @param int:$branchId
     * @param  string :$branchSection value(T,F)
     * @param $fitness
     */
    function __construct($branchId,$branchSection,$fitness)
    {
        $this->BranchId=$branchId;
        $this->setBranchSection($branchSection);
        $this->Fitness=$fitness;
    }
    public function ToString()
    {
        $Ret="";
        $Ret.="ID:".$this->BranchId;
        $Ret.=" BS:".$this->BranchSection;
        $Ret.=" Fit:".$this->Fitness;
        return $Ret;
    }

    /**
     * @return int
     */
    public function getBranchId()
    {
        return $this->BranchId;
    }

    /**
     * @param int $BranchId
     */
    public function setBranchId($BranchId)
    {
        $this->BranchId = $BranchId;
    }

    /**
     * @return string
     */
    public function getBranchSection()
    {
        return $this->BranchSection;
    }

    /**
     * @param string $BranchSection
     */
    public function setBranchSection($BranchSection)
    {
        $this->BranchSection = $BranchSection;
    }

    /**
     * @return int
     */
    public function getFitness()
    {
        return $this->Fitness;
    }

    /**
     * @param int $Fitness
     */
    public function setFitness($Fitness)
    {
        $this->Fitness = $Fitness;
    }
}