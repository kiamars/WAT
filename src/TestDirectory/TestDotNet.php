<?php
/**
 * Created by PhpStorm.
 * User: computer
 * Date: 11/27/2016
 * Time: 09:56 PM
 */
class DotNetCall4
{
    public  $ErrorMsg="";
    public
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

print_r($d->GetNormalInt(10,5,4));

print_r($d->GetNormalInt(10,5,4));

print_r($d->GetNormalInt(10,5,4));
*/