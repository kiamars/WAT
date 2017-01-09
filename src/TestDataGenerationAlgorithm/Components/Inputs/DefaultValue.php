<?php


class DefaultValue
{
    public $DefaultValue=null;
    public $NumberOfUsed=0;
    function __construct($value)
    {
        $this->NumberOfUsed=0;
        $this->DefaultValue=$value;
    }

    public function getDefaultValue()
    {
        return $this->DefaultValue;
    }
    public function setDefaultValue($DefaultValue)
    {
        $this->DefaultValue = $DefaultValue;
    }

    /**
     * @return int
     */
    public function getNumberOfUsed()
    {
        return $this->NumberOfUsed;
    }

    public function setNumberOfUsed($NumberOfUsed)
    {
        $this->NumberOfUsed = $NumberOfUsed;
    }
    public function  GetString()
    {
        $result="";
        $result.= "| DefaultValue:". $this->DefaultValue;
        $result.= "NumberOfUsed:".$this->NumberOfUsed." |";
        return $result;
    }
}