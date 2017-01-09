<?php
/**
 * Created by PhpStorm.
 * User: Mirzaee
 * Date: 11/2/2016
 * Time: 9:26 PM
 */
require_once "CommonConfig.php";
class PSOConfig extends CommonConfig
{
    public  static $VMax =200 ;//IMax-IMin

    public  static $w = .7298;////Inertiaweight w Varies from0.2to1
    public  static  $c1 = 2.05;//Accelerationconstants c1 and c2
    public  static $c2 = 2.05;//Accelerationconstants c1 and c2

    //public  static $MaxNumberOfExecution = 1000;//  Termination


    public static function  GetString()
    {
        $result=parent::GetString();
        $result.="|PSO_Config(w:";
        $result.=self::$w;

        $result.=",C1:";
        $result.=self::$c1;

        $result.=", C2:";
        $result.=self::$c2;

        $result.=", VMax:";
        $result.=self::$VMax.")";

        return $result;
    }
}
//echo PSOConfig::GetString();