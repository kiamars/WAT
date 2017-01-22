<?php
/**
 * Created by PhpStorm.
 * User: computer
 * Date: 11/27/2016
 * Time: 11:53 PM
 */

class DotNetCall
{
    public  $ErrorMsg="";
    private
        $DotNetClass=null;
    public function __construct()
    {
        try {
            $this->DotNetClass = new COM("DotNetCall.CallMatlab");
        }catch (Exception $ex)
        {
            $this->ErrorMsg
                = $ex->getMessage();
        }

    }

    public function GetNormalDouble($Size,$Mean,$Std)
    {
        $result=null;
        $g=$this->DotNetClass->GetNormalDouble($Size,$Mean,$Std);
        //  print_r($result);
        // var_dump(json_decode($result));
        $result=json_decode($g,true);
        return $result;
    }
    public function GetNormalInt($Size,$Mean,$Std)
    {
        $result=null;
        $g=$this->DotNetClass->GetNormalInt($Size,$Mean,$Std);
        //  print_r($result);
        // var_dump(json_decode($result));
        $result=json_decode($g,true);
        return $result;
    }
}

/*
$d=new DotNetCall();

print_r($d->GetNormalDouble(1,5,2));

*/