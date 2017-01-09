<?php
/**
 * Created by PhpStorm.
 * User: Mirzaee
 * Date: 10/30/2016
 * Time: 10:01 AM
 */
require_once "IGetSolution.inc";
abstract class Solution implements IGetSolution
{
    public $id=0;
    public $Interface=null;//InputsName=>InputsType(Ie,$GET,$POST)
    public $InputVector=null;//ValueForEachName
    public $fitness=PHP_INT_MAX;


    public function __construct()
    {
    }

    public function SetInterface($InterFace)
    {
        $this->Interface=$InterFace;
        foreach($InterFace as $InputName=>$InputInRequestType)
            $this->InputVector[$InputName]=0;
    }

    public function SetInputsValue($ParamsNameValue)
    {
        foreach($ParamsNameValue as $ParamsName=>$ParamValue)
            $this->InputVector[$ParamsName]=$ParamValue;
    }

    public function SetInputValue($InputName,$InputValue)
    {
        $this->InputVector[$InputName]=$InputValue;
    }
    public function GetInputValue($InputName)
    {
        return  $this->InputVector[$InputName];
    }



    public function GetSolution()
    {
        return $this;
    }

    public function GetStringOfInputsNameAndValues()
    {
        $r="Interface[";
        foreach($this->Interface as $InputName=>$InputType)
        {
            $r.="(N:".$InputName;
            $r.=",T:".$InputType;
            $r.=",V:".$this->InputVector[$InputName]."),";
        }
        $r.="]";
      return $r;
    }
    public abstract function GetNewSolutionsWithValueFor($paramName,$NewValuesForParam);

    function destroy()
    {
        $this->Interface =array();
        $this->InputVector=array();
    }
}

