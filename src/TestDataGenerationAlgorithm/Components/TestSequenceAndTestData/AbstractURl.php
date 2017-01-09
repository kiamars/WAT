<?php

/**
 * Created by PhpStorm.
 * User: Mirzaee
 * Date: 10/26/2016
 * Time: 3:19 PM
 */

require_once "UrlParametersList.php";
class AbstractURl
{
    public $Id = 0;
    public $BaseURL = "";//path and file name
    public $ParameterListManager = null;
    public $State;
    public $RequestType = "GET";//GetOrPost


    /**
     * @param $BaseURL
     * @param $UrlType :GET or POST
     *@param $arrayOfParams $Array_NameOfParamAsKeyAndValueOfParamAsArrayElementValue
     * @param
     */
    public function __construct($BaseURL, $UrlType, $arrayOfParams,$state)
    {
        $this->ParameterListManager = new UrlParametersList();
        $this->BaseURL = $BaseURL;
        $this->RequestType = $UrlType;
        $this->State=$state;
        $this->ParameterListManager->addListOfParam($arrayOfParams);
        $this->Id = sha1($this->BaseURL.$this->RequestType . $this->ParameterListManager->GetParamsNameAndValueInString());
    }
    public function GetHashOfBaseUrlAndParamName()
    {
        return sha1($this->BaseURL.$this->RequestType.$this->ParameterListManager->GetParamNameInString());
    }

    public function GetInterface()
    {
        $URLInterface=array();
        foreach($this->ParameterListManager->GetInterface() as $URLParamName)
            $URLInterface[trim($URLParamName)]=trim($this->RequestType);

        return $URLInterface;
    }

    public function GetParamsNameValue()
    {
        $URLInterface=$this->ParameterListManager->GetParametersNameValue();
        return $URLInterface;
    }

    public function GetRequestLength()
    {
        return count($this->State->RequestSequence);
    }

    /**
     * @return string of base path and Names
     * @return string
     */
    public function GetPathAndInterface()
    {
        $r="";
        $r.=$this->BaseURL." (";
        foreach($this->ParameterListManager->GetInterface() as $URLParamName)
            $r.=",".$URLParamName;
        $r.=")";
        return $r;
    }

    public function __clone()
    {
        if($this->State!==null)
                    $this->State=clone $this->State;

        if($this->ParameterListManager!==null)
        $this->ParameterListManager=clone $this->ParameterListManager;
    }


    public function getState()
    {
        return $this->State;
    }

    public function setState($State)
    {
        $this->State = $State;
        $this->Id=sha1($State->GetHashID().$this->BaseURL.$this->RequestType . $this->ParameterListManager->GetParamsNameAndValueInString());
    }

    public function addDefaultValuesTo($ParamName,$DefaultValuesOfParam)
    {
        $this->ParameterListManager->addDefaultValuesTo($ParamName,$DefaultValuesOfParam);
    }
    public function GetDefaultValuesArrayForUrlParams()
    {
        return$this->ParameterListManager->GetDefaultValuesArrayOfParams();
    }

    function destroy()
    {
        $this->State = null;
        $this->ParameterListManager = null;
    }
}

/*
$a=new AbstractURl("index.php","GET",array("a"=>"ali","b"=>124,"c"=>"haSan"),null);
echo $a->GetHashOfBaseUrlAndParamName();
echo "\n";
$a=new AbstractURl("index.php","GET",array("a"=>"haSan","b"=>124,"c"=>"ali"),null);
echo $a->GetHashOfBaseUrlAndParamName();
//print_r($a);
*/