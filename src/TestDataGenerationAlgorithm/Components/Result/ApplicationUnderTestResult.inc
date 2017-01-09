<?php
/**
 * Created by PhpStorm.
 * User: Computer
 * Date: 02/25/2016
 * Time: 11:34 PM
 */

class ApplicationUnderTestResult
{

    public $NumberOfRuns=0;
    public $NumberOfGeneration=0;
    public $ExecutionTime=0;
    public $PercentageOfCoverage=0;
    public $NumberOfCoveredBranches=0;
    public $ApplicationUnderTestName="";
    public $SearchAlgorithm="";
    public $AlgorithmConfig="";
    public $TestSuitResult="";
    Public $CoverageTable="";
    public $ReachedUrlAndNumberOfUseThemForGenerateTestData="";
    public $PercentOfCoverageInEachRun="";
    public $PercentOfCoverageInTime="";


    public function ResetCriticalVariables()
    {
        $this->AlgorithmConfig="";
        $this->ExecutionTime=0;
        $this->NumberOfGeneration=0;
        $this->NumberOfRuns=0;
        $this->CoverageTable="";
        $this->PercentageOfCoverage=0;
        $this->TestSuitResult=0;
    }
    public static  function GetFirstLineResult()
    {
        $result=array("SearchAlgorithm",
            "ApplicationUnderTestName",
            "NumberOfRun",
            "NumberOfGeneration",
            "ExecutionTime",
            "PercentageOfCoverage",
            "AlgorithmConfig",
            "OtherReports");
        return $result;
    }

    public function GetStringResult()
    {
        $result=array($this->SearchAlgorithm,
            $this->ApplicationUnderTestName,
            $this->NumberOfRuns,
            $this->NumberOfGeneration,
            $this->ExecutionTime,
            $this->PercentageOfCoverage,
            $this->AlgorithmConfig,
            $this->CoverageTable);
        return $result;
    }
    public  function  GetString()
    {
        $result="";
        $result.="NumberOfRun :" .$this->NumberOfRuns;
        $result.="</br>\n*****************************************</br>\n";
         $result.="NumberOfGeneration" .$this->NumberOfGeneration;
        $result.="</br>\n*****************************************</br>\n";
         $result.="ExecutionTime:".$this->ExecutionTime;
        $result.="</br>\n*****************************************</br>\n";
        $result.="PercentageOfCoverage:".$this->PercentageOfCoverage;
        $result.="</br>\n*****************************************</br>\n";
        $result.="ApplicationUnderTestName:".$this->ApplicationUnderTestName;
        $result.="</br>\n*****************************************</br>\n";
        $result.="SearchAlgorithm:".$this->SearchAlgorithm;
        $result.="</br>\n*****************************************</br>\n";
        $result.="AlgorithmConfig:".$this->AlgorithmConfig;
        $result.="</br>\n*****************************************</br>\n";
        $result.="TestSuitResult:".$this->TestSuitResult;
        $result.="</br>\n*****************************************</br>\n";
        $result.="OtherReports:".$this->CoverageTable;
        $result.="</br>\n*****************************************</br>\n";

        return $result;
    }
}