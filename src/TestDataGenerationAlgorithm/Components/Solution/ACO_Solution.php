<?php

/**
 * Created by PhpStorm.
 * User: computer
 * Date: 11/17/2016
 * Time: 12:17 AM
 */

require_once "Solution.php";

class ACO_Solution extends Solution
{
    public $Count=0;
    public $Record = array();
    public $Pheromone=1;

    public function __construct()
    {
        for($i=0;$i<ACOConfig::$ColonySize;$i++)
        {
            $this->Record[]=null;
        }
    }

    public function GetNewSolutionsWithValueFor($paramName, $NewValuesForParam,$IsNumeric=false)
    {
        $result=array();

        $Solution=null;
        foreach($NewValuesForParam as $NewValue)
        {

            $Solution=clone $this;

            $Solution->SetInputValue($paramName,$NewValue);
            if($IsNumeric)
                $Solution->Record[$paramName] =null;

            //initial to 1
            $result[]=$Solution;
        }
        return $result;
    }
}