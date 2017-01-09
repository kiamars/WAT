<?php
require_once "UtilityConstants.inc";
require_once "DefaultValue.php";
abstract class Input{

    public $NumberInEachGeneration=2;
    public $IndexInInputVector=1;
    public $Name="";
    protected $CurrentValue;
    public $InputType="";
    protected $DefaultValues= array();

    function __construct($indexInInputVector,$name,$inputType) {
        $this->NumberInEachGeneration=CommonConfig::$DefaultNumberInEachGenerationForCustomInput;
        $this->IndexInInputVector=$indexInInputVector;
        $this->Name=$name;
        $this->InputType=$inputType;
    }

    public  abstract function SetCurrentValue($value);
    public  abstract function GetRandomValue();

    public  abstract function AddDefaultValue($NewDefaultValue);
    public function AddDefaultValues($ArrayOfDefaultValues)
    {
        foreach ($ArrayOfDefaultValues as $NewDefaultValue) {
            $this->AddDefaultValue($NewDefaultValue);
        }
    }

    public function GetValidValueFormDefaultValue()
    {
        $r=null;
        if(count($this->DefaultValues)) {
            $NumberOfUses=array();
            foreach($this->DefaultValues as $DefaultValueObject)
            {
                $temp = (object)$DefaultValueObject;
                if (!empty($temp)) {
                    $NumberOfUses[]=$temp->getNumberOfUsed();
                }
            }

            $MinNumberOfUse =min($NumberOfUses);
            foreach($this->DefaultValues as $DefaultValueObject)
            {
                $temp = (object)$DefaultValueObject;
                if (!empty($temp)) {
                    if ($temp->getNumberOfUsed() <= $MinNumberOfUse) {
                        $temp->setNumberOfUsed($temp->getNumberOfUsed()+1);
                        $r = $temp->getDefaultValue();
                        return $r;
                    }
                }
            }
        }
        return $r;
    }

    /**
     * get array of valid value from default values, max size<=number of default values
     * @param $Number
     * @return array
     */
    public function GetValidValueFormDefaultValues($Number)
    {
        if($this->GetNumberOfDefaultValues()<$Number)
        {
            $Number=$this->GetNumberOfDefaultValues();
        }
        $ValidValues=array();
        for($i=0;$i<$Number;$i++)
            $ValidValues[]=$this->GetValidValueFormDefaultValue();
        return $ValidValues;
    }

    public  function GetString()
    {
        $result="";
        $result.="| Id: ".$this->IndexInInputVector;
        $result.="| Name:".$this->Name;
        $result.="| CurrentValue:".$this->CurrentValue;
        $result.="| InputType:".$this->InputType;
        $result.="| DefaultValues: ";
        return $result;
    }

    public function IsNumeric()
    {
        if($this->InputType===VariableType::DOUBLE ||$this->InputType===VariableType::FLOAT|| $this->InputType===VariableType::INTEGER)
            return true;
        return false;
    }

    public function GetCurrentValue()
    {
        return $this->CurrentValue;
    }
    public  function GetNumberOfDefaultValues()
    {
        return count($this->DefaultValues);
    }
    public function getInputType()
    {
        return $this->InputType;
    }
    public function getIndexInInputVector()
    {
        return $this->IndexInInputVector;
    }
    public function setIndexInInputVector($IndexInInputVector)
    {
        $this->IndexInInputVector = $IndexInInputVector;
    }

    public function GetCopyOfDefaultValueArrayObjects()
    {
        $r=array();
        foreach($this->DefaultValues as $DefaultValue ) {
            $r[]=clone  $DefaultValue;
        }
        return;
    }
    public function SetCopyOfDefaultValueObjects($ArrayOfDefaultValueObjects)
    {
        foreach($ArrayOfDefaultValueObjects as $NewDefaultValue ) {
            if (!array_key_exists($NewDefaultValue->DefaultValue, $this->DefaultValues))
                $this->DefaultValues[$NewDefaultValue->DefaultValue] = $NewDefaultValue;
        }
    }

    function destroy()
    {
        $this->DefaultValues = array();
    }
}

