<?php

/**
 * Created by PhpStorm.
 * User: computer
 * Date: 11/16/2016
 * Time: 07:48 PM
 */
require_once "CommonConfig.php";

class AVMConfig extends CommonConfig
{

    public function __construct()
    {
        CommonConfig::$MaxGenerationNumber=CommonConfig::$MaxGenerationNumber*((CommonConfig::$PopulationSizeForNumericInput+CommonConfig::$NumberOfRandomString+CommonConfig::$PopulationSizeForArrayInput)/3);
    }

    public static function  GetString()
    {
        $result=parent::GetString();

        return $result;
    }

}