<?php

/**
 * Created by PhpStorm.
 * User: Mirzaee
 * Date: 10/27/2016
 * Time: 8:37 PM
 */
class ClusterRecord
{
    public $AbstractUrlId=0;
    public $ListOfParamValues;
    public $NumberOFUse=0;

    public $AbstractURL=null;//the other field is used in clustering and this field is used when we want return abstractUrl Them self
    public function __construct($AbstractUrlId,$ListOfParamValues,$AbstractURL)
    {
        $this->AbstractUrlId=$AbstractUrlId;
        $this->ListOfParamValues=$ListOfParamValues;
        $this->AbstractURL=$AbstractURL;
    }

    function destroy()
    {
        $this->AbstractURL=null;
        $this->ListOfParamValues=array();
    }
}