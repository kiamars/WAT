<?php
require_once "BaseDirectory.php";


class  CommonConfig
{
    public static $MemLimit="1024M";
    public static $DefaultNumberInEachGenerationForCustomInput=2;

    //InputConsideration
    public static $MaxRequestLength = 5;//5
    public static $numberOfPrecisionForDoubleInput = 2;

    public static $MaxClusterLevelsInClusteringAbstractURL = 2;//2 ie on param is action param

    public static $MaxExecutionTime =1800;//600-1800

    public static function SetDataBaseConnection($db_host, $db_user, $db_pass, $db_name)
    {
        self::$db_host = $db_host;
        self::$db_user = $db_user;
        self::$db_pass = $db_pass;
        self::$db_name = "$db_name";
    }


    public static $MaxArraySize = 3;
    //data base connection
    public static $db_host = "localhost";
    public static $db_user = "root";
    public static $db_pass = "";
    public static $db_name = "faqforge";


    private static $DataBaseBackUpDirectory ='';
    public static $ResultRootDirectory = 'C:\xampp\htdocs\GenerateTestSequenceForWebApplication\TestScriptAndResults\ResultOfAlgorithm';//test data and ... for each algorithm
    public static $CSVResultRootDirectory = 'C:\xampp\htdocs\GenerateTestSequenceForWebApplication\TestScriptAndResults\ResultInCSV';
    public static $DataBaseInitializationDirectoryRootDirectory = 'C:\xampp\htdocs\GenerateTestSequenceForWebApplication\TestScriptAndResults\InitializeState\DataBaseInitialize';

    //population size(number,array,string)
    //number
    public static $PopulationSizeForNumericInput = 20;//5
    //array pop size
    public static $PopulationSizeForArrayInput = 2;//2
    //string
    public static $NumberOfRandomString = 2;//2Must be multiples of 6
    public static $MaxGenerationNumber = 5;//2


    //RandomChar
    public static $MaxNumberOfInsertedRandomChar = 1;
    public static $ProbabilityOfDeleteEachCharInAnString = 0.33;
    public static $ProbabilityOfUpdatingEachCharInAnString = 0.5;

    public static function GetBackUpDirectory()
    {
        if(self::$DataBaseBackUpDirectory==='')
            return self::$DataBaseInitializationDirectoryRootDirectory;
        return self::$DataBaseBackUpDirectory;
    }
    public static function SetDataBaseBackUpDirectory($BackUpRootDirectory)
    {
//if base directory is not exit create it
        if (!file_exists($BackUpRootDirectory))
        {
            if (!mkdir($BackUpRootDirectory, 0777, true)) {
                die('Failed to create folders....in log manger in specified path (ie.'.$BackUpRootDirectory.")");
            }
        }
        self::$DataBaseBackUpDirectory=$BackUpRootDirectory;
    }

    public static function  GetString()
    {
        $result = "|";
        $result .= "|MaxRequestLength:" . self::$MaxRequestLength;
        $result .= "|MaxURLClusterLevel:" . self::$MaxClusterLevelsInClusteringAbstractURL;
        $result .= "|MaxExecutionTime:" . self::$MaxExecutionTime;

        $result .= "|PopulationSizeForNumericInput:" . self::$PopulationSizeForNumericInput;
        $result .= "| NumberOfRandomString:" . self::$NumberOfRandomString;
        $result .= "|MaxGenerationNumber:" . self::$MaxGenerationNumber;

        //RandomChar
        $result .= "|RandomChar(IN:" . self::$MaxNumberOfInsertedRandomChar;
        $result .= ",POD:" . self::$ProbabilityOfDeleteEachCharInAnString;
        $result .= ",POU" . self::$ProbabilityOfUpdatingEachCharInAnString . ")";

        return $result;
    }
}


//class CommonConfig
//{
//
//    //InputConsideration
//    public static $MaxRequestLength = 5;//5
//    public static $numberOfPrecisionForDoubleInput = 2;
//
//    public static $MaxClusterLevelsInClusteringAbstractURL = 2;//2 ie on param is action param
//
//    public static $MaxExecutionTime = 1800;//600-1800
//
//    public static function SetDataBaseConnection($db_host, $db_user, $db_pass, $db_name)
//    {
//        self::$db_host = $db_host;
//        self::$db_user = $db_user;
//        self::$db_pass = $db_pass;
//        self::$db_name = "$db_name";
//    }
//
//
//    public static $MaxArraySize = 3;
//    //data base connection
//    public static $db_host = "localhost";
//    public static $db_user = "root";
//    public static $db_pass = "";
//    public static $db_name = "faqforge";
//
//    // public static $DataBaseBackUpDirectory='C:\xampp\htdocs\GenerateTestSequenceForWebApplication\TestDataGenerationAlgorithm\Components\StateManager\DataBaseManager\DataBaseBackUpDirectoryFiles';
//    public static $DataBaseBackUpDirectory = 'C:\ResultOfTDG_ForPHPProgram\DataBaseBackUpDirectoryFiles';
//    public static $ResultRootDirectory = "C:\ResultOfTDG_ForPHPProgram\Results";
//
//    //population size(number,array,string)
//    //number
//    public static $PopulationSizeForNumericInput = 5;//2
//    //array pop size
//    public static $PopulationSizeForArrayInput = 5;//2
//    //string
//    public static $NumberOfRandomString = 5;//2Must be multiples of 6
//    public static $MaxGenerationNumber = 2;//2
//
//
//    //RandomChar
//    public static $MaxNumberOfInsertedRandomChar = 1;
//    public static $ProbabilityOfDeleteEachCharInAnString = 0.33;
//    public static $ProbabilityOfUpdatingEachCharInAnString = 0.5;
//
//    public static function SetDataBaseBackUpDirectory($BackUpRootDirectory)
//    {
////if base directory is not exit create it
//        if (!file_exists($BackUpRootDirectory))
//        {
//            if (!mkdir($BackUpRootDirectory, 0777, true)) {
//                die('Failed to create folders....in log manger in specified path (ie.'.$BackUpRootDirectory.")");
//            }
//        }
//    self::$DataBaseBackUpDirectory=$BackUpRootDirectory;
//    }
//
//    public static function  GetString()
//    {
//        $result = "|";
//        $result .= "|MaxRequestLength:" . self::$MaxRequestLength;
//        $result .= "|MaxURLClusterLevel:" . self::$MaxClusterLevelsInClusteringAbstractURL;
//        $result .= "|MaxExecutionTime:" . self::$MaxExecutionTime;
//
//        $result .= "|PopulationSizeForNumericInput:" . self::$PopulationSizeForNumericInput;
//        $result .= "| NumberOfRandomString:" . self::$NumberOfRandomString;
//        $result .= "|MaxGenerationNumber:" . self::$MaxGenerationNumber;
//
//        //RandomChar
//        $result .= "|RandomChar(IN:" . self::$MaxNumberOfInsertedRandomChar;
//        $result .= ",POD:" . self::$ProbabilityOfDeleteEachCharInAnString;
//        $result .= ",POU" . self::$ProbabilityOfUpdatingEachCharInAnString . ")";
//
//        return $result;
//    }
//}
/*
echo ini_get("memory_limit")."\n";
ini_set("memory_limit","256M");
echo ini_get("memory_limit")."\n";

     //@ini_set( 'memory_limit', '32M' );
      //  @set_time_limit( 0 );
*/