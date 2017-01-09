<?php
/**
 * Created by PhpStorm.
 * User: Mirzaee
 * Date: 11/2/2016
 * Time: 9:16 PM
 */
require_once "Solution.php";
class PSO_Solution extends Solution
{
    public $Velocity=array();//velocity for input that are numeric
    public $PBest=null;

    public function __construct()
    {
        $this->PBest=clone $this;
        $this->PBest->PBest=null;
    }

    public function GetNewSolutionsWithValueFor($paramName, $NewValuesForParam,$IsNumeric=false)
    {
        $result=array();

        $Solution=null;
        foreach($NewValuesForParam as $NewValue)
        {
            $this->PBest->PBest=null;
            $Solution=clone $this;
            $Solution->PBest=clone $Solution;
            $Solution->PBest->PBest=null;

            $Solution->SetInputValue($paramName,$NewValue);
            if($IsNumeric)
                $Solution->Velocity[$paramName] =1;

            //initial to 1
            $result[]=$Solution;
        }
        return $result;
    }

    public function __clone()
    {
        if(!empty( $this->PBest))
        {
        $this->PBest->PBest=null;
          $this->PBest  =clone $this->PBest;
        $this->PBest->PBest=null;
        }
    }

    function destroy()
    {
        $this->PBest = null;
        $this->Velocity =array();
    }
}