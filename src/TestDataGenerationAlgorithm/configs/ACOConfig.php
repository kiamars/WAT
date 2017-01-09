<?php
require_once "CommonConfig.php";


class ACOConfig extends CommonConfig
{
    public static $ColonySize;//only to use in ACO solution ,that is assigned in ACOTDG
    public static $q0=.5 ;
    public static $T = 1;
    public static $p0=.3;
    public static $Phi=.5;
    public static $Alpha=.3;

    public static function  GetString()
    {
        $result=parent::GetString();
        $result.=",q0:";
        $result.=self::$q0;
        $result.=",p0:";
        $result.=self::$p0;

        $result.=",T:";
        $result.=self::$T;

        $result.=",Phi:";
        $result.=self::$Phi;

        $result.=",Alpha:";
        $result.=self::$Alpha;

        return $result;
    }
}