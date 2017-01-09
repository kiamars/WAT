<?php

/**
 * Created by PhpStorm.
 * User: Mirzaee
 * Date: 10/25/2016
 * Time: 5:56 PM
 */

require_once "Input.php";
class ITOB extends Input
{
    public  $Input=null;
    function __construct($indexInInputVector,$name,$currentValue=null,$defaultValues=array())
    {
        parent::__construct($indexInInputVector,$name,VariableType::INTEGER);

        if($currentValue==null)
            $this->SetCurrentValue($this->GetRandomValue());

        $this->AddDefaultValues($defaultValues);
    }
    public function SetCurrentValue($value)
    {
        $this->CurrentValue=(boolval($value));
    }

    public function GetRandomValue()
    {

            return rand(0,1) == 1;
    }

    public function AddDefaultValue($NewDefaultValue)
    {
        if(!array_key_exists($NewDefaultValue,$this->DefaultValues))
        $this->DefaultValues[$NewDefaultValue]=new DefaultValue(boolval($NewDefaultValue));
    }
}