<?php

require_once "IGetCurrentState.php";

interface IXBaseTDG extends IGetCurrentState
{
    public function SetInitialValue();
    public function InitialStatisticalResult();
    public function InitialCurrentSolution();
    public function InitialInputVector();
    public function InitialFirstState();
    public function InitialResourceManager();
    public function SetFunctionUnderTestName($FUT_Name);
    public function ConvertInputVectorToFUTInputType();
    public function InitialCoverageTable();
    public function ResetCriticalVariables();
    public function GenerateTestData();
    public function GetStringOfRunResult($Separator = ",");
    public function FUT();
    public function UPEL($BranchID, $BranchType, \TicksoftPHPSBTDF\Commons\FFER $FitnessFunctionEvaluationResult);
}