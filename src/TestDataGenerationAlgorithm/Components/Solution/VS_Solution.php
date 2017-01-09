<?php

require_once "Solution.php";

class VS_Solution extends Solution
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