<?php

/**
 * Created by PhpStorm.
 * User: Mirzaee
 * Date: 10/25/2016
 * Time: 4:36 PM
 */

require_once 'BaseDirectory.php';
require_once "IGenerateRandomValue.php";
require $BASEDIROFPROJECT . "/ExternalLibs/vendor/autoload.php";
    require_once $BASEDIROFPROJECT."/TestDataGenerationAlgorithm/Components/Inputs/AdvanceStringGenerator/RandomGeneratorFromRegex.php";
use Faker\Factory;

class RandomValueGeneratorForStrings implements  IGenerateRandomValue
{
    public static $SeededRegex=array();

    public static $Types=array("name","url","userAgent","uuid","windowsPlatformToken","word","year","address","text","buildingNumber" ,"century","colorName","city","companyEmail","countryISOAlpha3","freeEmail");


    public $TypeOfCommonInput="name";
    public function __construct()
    {
        $this->DG =Factory::create();
        //$this->DG=Faker\Factory::create('fa_IR');
    }

    /**
     * Get One valid seeded value
     * @return mixed
     */
    public function GetOrGenerateValidSeededValue()
    {

        $Result="";
        if(intval($this->TypeOfCommonInput))
            {
            $regRx=self::$SeededRegex[intval($this->TypeOfCommonInput)];
            $RegExGenerator=new RandomGeneratorFromRegex($regRx);
            $Result=$RegExGenerator->GetOrGenerateValidSeededValue();
            }else
            {
            switch ($this->TypeOfCommonInput) {
                case "name":
                    $Result=$this->DG->name;
                    break;
                case "url":
                    $Result= $this->DG->url;
                    break;
                case "userAgent":
                    $Result= $this->DG->userAgent;
                    break;
                case "uuid":
                    $Result= $this->DG->uuid;
                    break;
                case "windowsPlatformToken":
                    $Result= $this->DG->windowsPlatformToken;
                    break;
                case "word":
                    $Result= $this->DG->word;
                    break;
                case "year":
                    $Result= $this->DG->year;
                    break;
                case "address":
                    $Result= $this->DG->address;
                    break;
                case "text":
                    $Result= $this->DG->text(150);
                    break;
                case "buildingNumber":
                    $Result= $this->DG->buildingNumber;
                    break;
                case "century":
                    $Result= $this->DG->century;
                    break;
                case "colorName":
                    $Result= $this->DG->colorName;
                    break;
                case "city":
                    $Result= $this->DG->city;
                    break;
                case "companyEmail":
                    $Result= $this->DG->companyEmail;
                    break;
                case "countryISOAlpha3":
                    $Result= $this->DG->countryISOAlpha3;
                    break;
                case "freeEmail":
                    $Result= $this->DG->freeEmail;
                    break;

                default:
                    $Result= $this->DG->text(100);
                }
            }





        return $Result;
    }

    public function GenerateRandomValues($Number)
    {
        $Number=intval($Number);
        $ReturnValues=array();

        if($Number<=1)
        {
            $index=array_rand(self::$Types,1);
            $this->TypeOfCommonInput=self::$Types[$index];
            $ReturnValues[]=$this->GetOrGenerateValidSeededValue();

        }else if($Number>0)
        {
        for($i=1;$i<=$Number;$i++)
            {
            $index=array_rand(self::$Types,1);
            $this->TypeOfCommonInput=self::$Types[$index];
            $ReturnValues[]=$this->GetOrGenerateValidSeededValue();
            }
        }
        return $ReturnValues;
    }
    function destroy()
    {
        $this->DG=null;
    }

}
/*
$a=new RandomValueGeneratorForStrings();
$a->GetTest();
echo "\n";
print_r(
    $a->GenerateRandomValues(20));*/