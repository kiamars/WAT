<?php

/**
 * Created by PhpStorm.
 * User: Mirzaee
 * Date: 10/27/2016
 * Time: 11:17 AM
 */
require_once"UrlParameter.php";
class UrlParametersList
{
    public $list=array();

    //$Array_NameOfParamAsKeyAndValueOfParamAsArrayElementValue
    public function addListOfParam($arrayOfParams)
    {
        foreach($arrayOfParams as $ParamName=>$ParamValue)
        {
            $this->add($ParamName,$ParamValue);
        }
    }
    public function add($ParamName,$ParamValue)
    {
        $this->list[$ParamName]=new UrlParameter($ParamName,$ParamValue);
    }

    /**
     * return the name of params in an array
     * @return array
     */
    public function GetInterface()
    {
        $ret=array();
        foreach($this->list as $urlParamObject)
        {
            $urlParam=(object)$urlParamObject;
            $ret[]=$urlParam->parameterName;
        }
        return $ret;
    }

    /**
     * get array of (Par1=>val1,par2=>val2,$par3=>val3)
     * if not exist any param then return an empty array
     *
     * @return array
     */
    public function GetParametersNameValue()
    {
        $ret=array();
        foreach($this->list as $urlParamObject)
        {
            $urlParam=(object)$urlParamObject;
            $ret[$urlParam->parameterName]=$urlParam->ParameterValue;
        }
        return $ret;
    }

    public function GetParamNameInString()
    {
        $ret="";
        foreach($this->list as $urlParamObject)
        {
            $urlParam=(object)$urlParamObject;
            $ret.=$urlParam->parameterName;
        }
        return $ret;
    }
    public function GetParamsNameAndValueInString()
    {
        $ret="";
        foreach($this->list as $urlParamObject)
        {
            $urlParam=(object)$urlParamObject;
            $ret.=$urlParam->parameterName."|";
            if($urlParam->parameterName===null ||$urlParam->parameterName==="")
                $ret.="emptyOrNull;";
                else
                {
                    if(is_array($urlParam->ParameterValue))
                    {$ret.="[";
                        foreach($urlParam->ParameterValue as $ParValue)
                        $ret.=$ParValue.",";
                        $ret.="];";
                    }else
                    {
                        $ret.=$urlParam->ParameterValue.";";
                    }
                }

        }
        return $ret;
    }

    public function GetCurrentValueFor($ParameterName)
    {
        return $this->list[$ParameterName];
    }

    public function __clone()
    {
        foreach($this->list as &$Param){
            $Param=clone $Param;
        }
    }

    public function addDefaultValuesTo($ParamName,$DefaultValuesOfParam)
    {
        $UrlParam=(Object) $this->list[$ParamName];
        if (!empty($UrlParam)) {
            $UrlParam->DefaultValuesForParam=$DefaultValuesOfParam;
        }
    }
    public function GetDefaultValuesArrayOfParams()
    {
        $r=array();
        foreach($this->list as $ParamName=>$urlParam)
        {
            $r[$ParamName]=$urlParam->DefaultValuesForParam;
        }
        return $r;
    }


    function destroy()
    {
        $this->list=array();;
    }
}