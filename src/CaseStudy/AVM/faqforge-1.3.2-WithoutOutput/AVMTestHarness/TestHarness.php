<?php
//session_start();
require_once "BaseDirectory.php";
require $BASEDIROFPROJECT . "/TestScriptAndResults/InitializeState/faqforgInitializeState.php";

require_once "AVMPageObjectResolver.php";

//ini_set("memory_limit","512M");
// Report all errors except E_NOTICE
// This is the default value set in php.ini
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
// For PHP < 5.3 use: E_ALL ^ E_NOTICE
ini_set('memory_limit', CommonConfig::$MemLimit);
//ini_set('memory_limit', '-1');
echo "\n memory_limit" . ini_get("memory_limit") . "\n";


require_once "BaseDirectory.php";
require_once $BASEDIROFPROJECT . "/TestDataGenerationAlgorithm/SBTDGA.php";
require_once $BASEDIROFPROJECT . "/CaseStudy/PSO/faqforge-1.3.2-WithoutOutput/FilePath.php";
//IncludePages


$PageObjectResolver = new AVMPageObjectResolver();
$PageObjectResolver->Register(FilePath::Index_php);
$PageObjectResolver->Register(FilePath::Admin_LogOut_php);
$PageObjectResolver->Register(FilePath::Admin_LogIn_php);
$PageObjectResolver->Register(FilePath::Admin_Index_php);


$numberOfAllBranchInProgramUnderTest = 150;//75
$TestHarness = new SBTDGA($PageObjectResolver, $numberOfAllBranchInProgramUnderTest,"faqforge","AVM");
$TestHarness->SetDBConnection("localhost", "root", "", "faqforge");
$TestHarness->Initialisation(FilePath::Index_php, "GET", array());
$TestHarness->GenerateTestSequenceAndData();
$RunResult="";
$RunResult=$TestHarness->GetResultAsString();
