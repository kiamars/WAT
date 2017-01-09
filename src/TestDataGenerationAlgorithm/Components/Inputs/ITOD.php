<?php
require_once "Input.php";
class ITOD extends Input
{
    public $Max = PHP_INT_MAX;
    public $Min = -999999;
    public $r = 1;
    public $NumberOfPrecision = 1;

    function __construct($indexInInputVector, $name, $currentValue = null, $dMin = -999999, $dMax = PHP_INT_MAX, $defaultValues = array(), $numberOfPrecision = 2)
    {
        parent::__construct($indexInInputVector, $name, VariableType::DOUBLE);
        $this->Min = $dMin;
        $this->Max = $dMax;
        if ($currentValue == null)
            $this->SetCurrentValue(($this->Min + $this->Max) / 2);
        // $this->SetCurrentValue(mt_rand($this->Min,$this->Max));
        $this->r = .5 * ($this->Max - $this->Min);
        $this->AddDefaultValues($defaultValues);
        $this->NumberOfPrecision = $numberOfPrecision;
    }

    public function SetCurrentValue($value)
    {
        if ($value > $this->Max)
            $this->CurrentValue = $this->Max;
        else if ($value < $this->Min)
            $this->CurrentValue = $this->Min;
        else
            $this->CurrentValue = $value;
    }

    public function GetRandomValue()
    {
        if(count($this->DefaultValues))
        {
            $NumberOfUses=array();
            foreach($this->DefaultValues as $DefaultValueObject)
            {
                $temp = (object)$DefaultValueObject;
                if (!empty($temp)) {
                    $NumberOfUses[]=$temp->getNumberOfUsed();
                }
            }
            $MinNumberOfUse =min($NumberOfUses);
            if($MinNumberOfUse<1)
            {
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

        }
        return mt_rand() / mt_getrandmax() + mt_rand($this->Min, $this->Max - 1);
    }

    public function AddDefaultValue($NewDefaultValue)
    {
        if (is_numeric($NewDefaultValue)) {
            if(!array_key_exists($NewDefaultValue,$this->DefaultValues))
                $this->DefaultValues[$NewDefaultValue] = new DefaultValue($NewDefaultValue);
        }
    }

    public function GetString()
    {
        $result="";
        $result.=parent::GetString();

        $result.="| Max:". $this->Max;
        $result.="| Min:".$this->Min;
        $result.="| NumberOfPrecision:".$this->NumberOfPrecision;
        return $result;
    }
}
