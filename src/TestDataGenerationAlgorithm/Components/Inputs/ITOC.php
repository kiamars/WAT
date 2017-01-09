<?php

/**
 * Created by PhpStorm.
 * User: computer
 * Date: 11/21/2016
 * Time: 12:47 AM
 */
require_once "Input.php";
class ITOC extends Input
{
    protected $ValueGenerator=null;
    protected $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0';

    function __construct($indexInInputVector,$name,$currentValue,$defaultValues=array())
    {
        parent::__construct($indexInInputVector,$name,VariableType::STRING);
        $this->SetCurrentValue($currentValue);

        $this->DefaultValues=array();
        $this->AddDefaultValues($defaultValues);

    }



    public function SetCurrentValue($value)
    {
        $this->CurrentValue=$value;
    }

    public function GetRandomValue()
    {
        $ReturnValue="M";
        $RandomKey=mt_rand(0,strlen($this->characters)-1);
//        $RandomKey=array_rand($this->characters);
        $ReturnValue=$this->characters[$RandomKey];
        return $ReturnValue;
    }

    public function AddDefaultValue($NewDefaultValue)
    {
        if(!array_key_exists($NewDefaultValue,$this->DefaultValues))
            $this->DefaultValues[$NewDefaultValue]=new DefaultValue($NewDefaultValue);
    }

    public function GetNeighboursOfThisWithSeededAndRandomValue($NumberOfNeighbor)
    {
        $ReturnValues=array();

        for($i=0;$i<$NumberOfNeighbor;$i++)
        {
            if(mt_rand() / mt_getrandmax() > .5)
            {
                $ReturnValues[]=$this->GetRandomValue();
            }
            else
            {
                $ReturnValues[]=$this->GetValidValueFormDefaultValue();
            }
        }

        return $ReturnValues;
    }

    public function GetString()
    {
        $result="";
        $result.=parent::GetString();

        $result.="| MaxLength:". $this->MaxLength;
        $result.="| MinLength:".$this->MinLength;

        return $result;
    }
}

/*
$a=new ITOS("1","context","",null,array("ali"));
print_r($a->GetNeighboursOfThisWithSeededAndRandomValue(10));
echo $a->GetString();*/
//echo microtime();