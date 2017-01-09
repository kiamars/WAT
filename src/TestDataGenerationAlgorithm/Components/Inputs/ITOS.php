<?php
require_once "Input.php";
require_once "IGenerateRandomValue.php";
require_once "RandomValueGeneratorForStrings.php";

class ITOS extends Input
{
    protected $ValueGenerator=null;
    public static $GenerateRandomValueFromRegex=null;//manually set this
    public $MinLength=0;
    public $MaxLength=200;
    function __construct($indexInInputVector,$name,$currentValue,IGenerateRandomValue $ValueGenerator=null,$defaultValues=array(),$minLength=0,$maxLength=200)
    {
        parent::__construct($indexInInputVector,$name,VariableType::STRING);
        $this->MaxLength=$maxLength;
        $this->MinLength=$minLength;
        $this->SetCurrentValue($currentValue);
        $this->DefaultValues=array();
        $this->AddDefaultValues($defaultValues);

        if(is_null($ValueGenerator))
        {
            $this->ValueGenerator=new RandomValueGeneratorForStrings();
        }else
        {
            $this->ValueGenerator=$ValueGenerator;
        }
    }

    public $ISeeder=null;//other seeder than default value

    public function SetCurrentValue($value)
    {
        $this->CurrentValue=$value;
    }

    public function GetRandomValue()
    {

        $NumberOfInsertedRandomChar = CommonConfig::$MaxNumberOfInsertedRandomChar;;
        $ProbabilityOfDeleteEachCharInAnString = CommonConfig::$ProbabilityOfDeleteEachCharInAnString;;
        $ProbabilityOfUpdatingEachCharInAnString = CommonConfig::$ProbabilityOfUpdatingEachCharInAnString;

        $ReturnValue = null;
        if (mt_rand() / mt_getrandmax() > .5) {

            if(mt_rand() / mt_getrandmax() > .4) {
                $ReturnValue = $this->ValueGenerator->GenerateRandomValues(1);
                $ReturnValue = $ReturnValue[0];
            }else
            {
                //from default value
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
                                    $ReturnValue = $temp->getDefaultValue();
                                }
                            }
                        }
                    }
                }
                else{
                    //no default value exist
                    $ReturnValue = $this->ValueGenerator->GenerateRandomValues(1);
                    $ReturnValue = $ReturnValue[0];
                }
            }

        } else {
            //Invalid values
            if (mt_rand() / mt_getrandmax() > .4 && mt_rand() / mt_getrandmax() < .6)
                $ReturnValue = null;
            else if (mt_rand() / mt_getrandmax() < .2)
                $ReturnValue = "";
            else if (mt_rand() / mt_getrandmax() > .2 && mt_rand() / mt_getrandmax() < .4) {
                //for Max Length
                $var = "";
                for ($i = 0; $i < $this->MaxLength; $i++) {
                    $var = $var . "a";
                }
                $ReturnValue = $var;
            } else {
                if ($this->CurrentValue !== null and $this->CurrentValue !== "")
                    $ReturnValue = $this->MutateCurrentValue($NumberOfInsertedRandomChar, $ProbabilityOfDeleteEachCharInAnString, $ProbabilityOfUpdatingEachCharInAnString);
                else
                {
                    $ReturnValue = $this->ValueGenerator->GenerateRandomValues(1);
                    $ReturnValue=$ReturnValue[0];
                }
            }
        }
        return $ReturnValue;
    }

    public function AddDefaultValue($NewDefaultValue)
    {
        if(!array_key_exists($NewDefaultValue,$this->DefaultValues))
            $this->DefaultValues[$NewDefaultValue]=new DefaultValue($NewDefaultValue);
    }
    public function GetLength()
    {
        return strlen($this->CurrentValue);
    }

    public function GetNeighboursOfThisWithSeededAndRandomValue($NumberOfNeighbor)
    {
        $NumberOfInsertedRandomChar=CommonConfig::$MaxNumberOfInsertedRandomChar;;
        $ProbabilityOfDeleteEachCharInAnString=CommonConfig::$ProbabilityOfDeleteEachCharInAnString;;
        $ProbabilityOfUpdatingEachCharInAnString=CommonConfig::$ProbabilityOfUpdatingEachCharInAnString;

        $ReturnValues=null;
        //valid value form default values
        $ReturnValues=$this->GetValidValueFormDefaultValues($NumberOfNeighbor*3/6);

        //GetRandomValue
        $RandomValues=$this->ValueGenerator->GenerateRandomValues($NumberOfNeighbor*2/6);
        for($i=0;$i<count($RandomValues);$i++)
        {
            $ReturnValues[]=$RandomValues[$i];
        }

        //Invalid values
        if(mt_rand() / mt_getrandmax()>.5)
        $ReturnValues[]=null;
        if(mt_rand() / mt_getrandmax()<.5)
        $ReturnValues[]="";

        if(mt_rand() / mt_getrandmax()>.5)
        {
            //for Max Length
            $var="";
            for($i=0;$i<$this->MaxLength;$i++)
            {
                $var=$var."a";
            }
            $ReturnValues[]=$var;
        }

        if(mt_rand() / mt_getrandmax()<.5) {
            //mutate current value
            if ($this->CurrentValue !== null and $this->CurrentValue !== "")
                $ReturnValues[] = $this->MutateCurrentValue($NumberOfInsertedRandomChar, $ProbabilityOfDeleteEachCharInAnString, $ProbabilityOfUpdatingEachCharInAnString);
        }

        //if number of generated value less than required
        if(count($ReturnValues)<$NumberOfNeighbor)
        {
            $RandomValues=$this->ValueGenerator->GenerateRandomValues($NumberOfNeighbor-count($ReturnValues));
            for($i=0;$i<count($RandomValues);$i++)
            {
                $ReturnValues[]=$RandomValues[$i];
            }
        }

        return $ReturnValues;
    }
    public function MutateCurrentValue($MaxNumberOfInsertedRandomChar = 1, $ProbabilityOfDeleteEachCharInAnString = .33, $ProbabilityOfUpdatingEachCharInAnString = .5)
    {
        $ReturnValue=$this->CurrentValue;

        //for add $NumberOfInsertedRandomChar in random position
        for($i=1;$i<=$MaxNumberOfInsertedRandomChar && (mb_strlen($ReturnValue)<= ($this->MaxLength+1));$i++){
            //add a Random char in Random position
            $CharAsciiCode=mt_rand(0,255);
            $RandomChar=chr($CharAsciiCode);
            $RandomPosition = rand(0,strlen($ReturnValue));
            if($RandomPosition<(strlen($ReturnValue)-1))
            {
                $ReturnValue= substr($ReturnValue,0,$RandomPosition).$RandomChar.substr($ReturnValue,$RandomPosition);
            }else
            {
                //add char randomly in first or end
                if(mt_rand(0,1)==1)
                {
                    $ReturnValue=$ReturnValue.$RandomPosition;
                }else
                {
                    $ReturnValue=$RandomPosition.$ReturnValue;
                }
            }
        }

        //for Delete each char
        for($i=0;$i<mb_strlen($ReturnValue);$i++){
            $RandomVarB0And1= mt_rand() / mt_getrandmax();
            if($RandomVarB0And1<$ProbabilityOfDeleteEachCharInAnString)
            {
                $ReturnValue=str_replace($ReturnValue[$i],'',$ReturnValue);
            }
        }

        //for Update each char with Random char
        for($i=0;$i<mb_strlen($ReturnValue);$i++){
            $RandomVarB0And1= mt_rand() / mt_getrandmax();
            if($RandomVarB0And1<$ProbabilityOfUpdatingEachCharInAnString)
            {
                $ASCCICode=mt_rand(0,255);//replace with random char
                $ReturnValue=str_replace($ReturnValue[$i],chr($ASCCICode),$ReturnValue);
            }
        }
        return $ReturnValue;
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