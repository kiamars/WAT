<?php

/**
 * Created by PhpStorm.
 * User: Mirzaee
 * Date: 10/25/2016
 * Time: 6:03 PM
 */
/**
 * array that Element is Same
 * Class ATEIS
 * @package TicksoftPHPSBTDF\Commons
 */

require_once "Input.php";

class ITOArray extends Input
{
    /**
     * Mas Size Of Array
     * @var int
     */
    public static $MaxArraySize = 5;

    public $ArrayElementsDataModeler = null;


    function __construct($indexInInputVector, $name, $currentValue = null, $MaxSizeOfArray = 3,Input $ArrayElementDataModeler)
    {
        self::$MaxArraySize=$MaxSizeOfArray;
        if (ArrayElementsType::DOUBLE === $ArrayElementDataModeler->getInputType() || $ArrayElementDataModeler->getInputType() === ArrayElementsType::DOUBLE) {
            $this->ArrayElementsDataModeler = $ArrayElementDataModeler;
        } else if (ArrayElementsType::INTEGER === $ArrayElementDataModeler->getInputType()) {
            $this->ArrayElementsDataModeler = $ArrayElementDataModeler;
        } else if (ArrayElementsType::STRING === $ArrayElementDataModeler->getInputType()) {
            $this->ArrayElementsDataModeler = $ArrayElementDataModeler;

        } else {
            echo "\n <br> in".__FILE__.__LINE__."array element type not allowed";
        }


        if ($currentValue == null) {
            $this->CurrentValue = $this->GetRandomValue();
        } else {
            $this->CurrentValue = $currentValue;
        }
        parent::__construct($indexInInputVector, $name, VariableType::_ARRAY);
    }

    public function GetString()
    {
        $result = "";

        $result .= "| Id: " . $this->IndexInInputVector;
        $result .= "| ArrayName:" . $this->Name;
        $result .= "| CurrentValue:" . var_export($this->CurrentValue, true);
        $result .= "| InputType:" . $this->InputType;

        $result .= "| MaxArraySize:" . $this->MaxArraySize;
        $result .= "| ArrayElementsTypeModel:" . $this->ArrayElementsTypeModel->GetString();
        return $result;
    }

    public function SetCurrentValue($value)
    {
        $this->CurrentValue = $value;
    }

    /**
     * return random value for this input
     * @return mixed
     */
    public function GetRandomValue()
    {
        $RandomSize = mt_rand(0, self::$MaxArraySize);

        $ReturnValue = array();
        $newValue=null;
        if ($RandomSize > 0) {
            for ($i = 0; $i < $RandomSize; $i++) {

                    $newValue=$this->ArrayElementsDataModeler->GetRandomValue();
                if(!in_array($newValue,$ReturnValue, TRUE))
                {
                    $ReturnValue[] =$newValue;
                }
            }
        } else {
            return $ReturnValue;
        }
        return $ReturnValue;
    }

    public function AddDefaultValue($NewDefaultValue)
    {
       $this->ArrayElementsDataModeler->AddDefaultValue($NewDefaultValue);
    }

    public function AddDefaultValues($ArrayOfDefaultValues)
    {
        $this->ArrayElementsDataModeler->AddDefaultValues($ArrayOfDefaultValues);
    }
}

class ArrayElementsType
{
    const INTEGER = 'integer';
    const FLOAT = 'float';
    const STRING = 'string';
    const DOUBLE = 'double';
}
/*
require_once"ITOI.php";
$ArrayElementDataType=new ITOI(1,"a",null,-100,100);
$a=new ITOArray(1,"a[]",null,5,$ArrayElementDataType);
print_r(
$a->GetRandomValue());*/