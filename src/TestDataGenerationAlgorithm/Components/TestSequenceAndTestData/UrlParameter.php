<?php

/**
 * Created by PhpStorm.
 * User: Mirzaee
 * Date: 10/27/2016
 * Time: 11:15 AM
 */
class UrlParameter
{
    public $parameterName="";
    public $ParameterValue="";
    public $DefaultValuesForParam= array();
    public function __construct($parameterName,$ParameterValue)
    {
        $this->parameterName=$parameterName;
        $this->ParameterValue=$ParameterValue;
    }
    function destroy()
    {
        $this->DefaultValuesForParam = array();
    }
}