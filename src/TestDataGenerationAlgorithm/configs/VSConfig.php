<?php

/**
 * Created by PhpStorm.
 * User: computer
 * Date: 11/22/2016
 * Time: 09:52 PM
 */
require_once "CommonConfig.php";

class VSConfig  extends CommonConfig
{
    public static $GinvDirectoryAndFileName = "C:\\input\\Ginv.csv";
    public static $x=0.09;

    public static function GetGInv()
    {
        $r=null;
        $r=file(self::$GinvDirectoryAndFileName);
        return $r;
    }
    public static function  GetString()
    {
        $result="";
        $result.=parent::GetString();
        $result.="|GinvDirectoryAndFileName:";
        $result.=self::$GinvDirectoryAndFileName;
        $result.="|x:";
        $result.=self::$x;

        return $result;
    }
}
