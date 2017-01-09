<?php

/**
 * Created by PhpStorm.
 * User: computer
 * Date: 11/16/2016
 * Time: 07:35 PM
 */
require_once "Solution.php";
class AVM_Solution extends Solution
{

    public function GetNewSolutionsWithValueFor($paramName, $NewValuesForParam,$IsNumeric=false)
    {
        $result=array();

        $Solution=null;
        foreach($NewValuesForParam as $NewValue)
        {
            $Solution=clone $this;

            $Solution->SetInputValue($paramName,$NewValue);
            /*if($IsNumeric)
                $Solution->Velocity[$paramName] =1;
            */

            $result[]=$Solution;
        }

        return $result;
    }
}