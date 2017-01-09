<?php
require_once "Input.php";
class ITOI extends Input
{
    public $Max=PHP_INT_MAX;
    public $Min=-999999999;
    public $r=1;

    function __construct($indexInInputVector,$name,$currentValue=null,$iMin=-999999999,$iMax=PHP_INT_MAX,$defaultValues=array())
    {
        parent::__construct($indexInInputVector,$name,VariableType::INTEGER);
        $this->Min=$iMin;
        $this->Max=$iMax;
        $this->r=0.5*($this->Max - $this->Min);
        if($currentValue==null)
            $this->SetCurrentValue(($this->Min+$this->Max)/2);
        //$this->SetCurrentValue(mt_rand($this->Min,$this->Max));

        $this->AddDefaultValues($defaultValues);
    }


    public function AddDefaultValue($NewDefaultValue)
    {
        if(is_numeric($NewDefaultValue))
        {
            if(!array_key_exists($NewDefaultValue,$this->DefaultValues))
                $this->DefaultValues[$NewDefaultValue]=new DefaultValue(intval($NewDefaultValue));
        }
    }

    public function SetCurrentValue($value)
    {
        if($value>$this->Max)
            $this->CurrentValue=$this->Max;
        else if($value<$this->Min)
            $this->CurrentValue=$this->Min;
        else
            $this->CurrentValue=$value;
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
        $r= mt_rand($this->Min,$this->Max);
        return intval($r);
    }

    public function GetString()
    {
        $result="";
        $result.=parent::GetString();

        $result.="| Max:". $this->Max;
        $result.="| Min:".$this->Min;
        return $result;
    }
}

/*$n=new ITOI(0,"ali",10,1,10,array(1,2,3));
print $n->GetString();
print_r($n->GetValidValueFormDefaultValues(5));
echo"************************";
print_r($n->GetValidValueFormDefaultValues(2));
echo"************************";
print_r($n->GetValidValueFormDefaultValues(2));*/