<?php
/**
 * Created by PhpStorm.
 * User: Computer
 * Date: 6/25/2016
 * Time: 2:37 AM
 */


require_once 'BaseDirectory.php';
require $BASEDIROFPROJECT . "/ExternalLibs/vendor/autoload.php";
use Faker\Factory;

class SeederValueForCommonInput
{
    public $TypeOfCommonInput="name";
    public function __construct($typeOfCommonInput)
    {
        $this->TypeOfCommonInput=$typeOfCommonInput;
         $this->DG =Factory::create();
        //$this->DG=Faker\Factory::create('fa_IR');
    }

    public function GenerateRandomValue($Number)
    {
        $ReturnValues=array();

        $Types=array("name","url","userAgent","uuid","windowsPlatformToken","word","year","address","text","buildingNumber" ,"century","colorName","city","companyEmail","countryISOAlpha3","freeEmail");

        if($Number<count($Types))
            $indexes=array_rand($Types,$Number);
        else
            $indexes=array_rand($Types,count($Types));

        foreach($indexes as $index)
        {
            $this->TypeOfCommonInput=$Types[$index];
            $ReturnValues[]=$this->GetOrGenerateValidSeededValue();
        }
        if($Number>count($Types))
            for($i=count($Types)-$Number;$i>=0;$i--)
                $ReturnValues[]=$this->GetOrGenerateValidSeededValue();


        return $ReturnValues;
    }
    /**
     * Get One valid seeded value
     * @return mixed
     */
    public function GetOrGenerateValidSeededValue()
    {

        $Result="";
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
        return $Result;
    }
}

class CommonInputType
{
    const name="name";
    const url="url";
    const userAgent="userAgent";
    const uuid ="uuid";
    const windowsPlatformToken="windowsPlatformToken";
    const word="word";
    const year="year";
    const address="address";
    const text="text";
    const buildingNumber="buildingNumber";
    const century="century";
    const colorName="colorName";const city="city";
    const companyEmail= "companyEmail";
    const countryISOAlpha3="countryISOAlpha3";
    const freeEmail="freeEmail";
}


$a=new SeederValueForCommonInput("companyEmail");
print_r(
$a->GenerateRandomValue(20));