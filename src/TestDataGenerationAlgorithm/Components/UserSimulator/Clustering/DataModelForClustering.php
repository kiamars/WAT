<?php
/**
 * Created by PhpStorm.
 * User: Mirzaee
 * Date: 10/27/2016
 * Time: 8:37 PM
 */
require_once "ClusterRecord.php";

class DataModelForClustering
{
    public $ParamsName = array();

    public $ListOfClusterRecord;
    public $ClusterLevel = 1;
    public $ListOfClusteringParamsName = array();
    public $NumberOfDistinctWordForEachParam;

    public $NumberOfUsedRecordInThisCluster = 0;

    public function __construct($ParamsName, $ClusterLevel, $ListOfClusteringParamsName)
    {
        $this->ListOfClusterRecord = array();
        $this->ClusterLevel = $ClusterLevel;
        $this->ListOfClusteringParamsName = $ListOfClusteringParamsName;
        $this->NumberOfDistinctWordForEachParam = array();

        $this->ParamsName = $ParamsName;
        foreach ($ParamsName as $paramName) {
            $this->NumberOfDistinctWordForEachParam[$paramName] = array();
        }
    }

    public function AddNewRecord(AbstractURl $AbstractUlr)
    {

        $ParamsNameValues = null;
        $ParamsNameValues = $AbstractUlr->ParameterListManager->GetParametersNameValue();
        if ($ParamsNameValues !== null) {

            if (count($ParamsNameValues) === 0) {
                $this->ListOfClusterRecord[$AbstractUlr->Id] = new ClusterRecord($AbstractUlr->Id, $ParamsNameValues, $AbstractUlr);
                $this->ClusterLevel = CommonConfig::$MaxClusterLevelsInClusteringAbstractURL;
            } else {
                $this->ListOfClusterRecord[$AbstractUlr->Id] = new ClusterRecord($AbstractUlr->Id, $ParamsNameValues, $AbstractUlr);
                $this->UpdateNumberOfDistinctValue($ParamsNameValues);
            }
        }
    }

    protected function UpdateNumberOfDistinctValue($ParamsNameValues)
    {
        foreach ($ParamsNameValues as $paramName => $ParValue) {
            // if($ParValue!==null&& $ParValue!=="")
            {

                if (isset($this->NumberOfDistinctWordForEachParam[$paramName][$ParValue])) {
                    $this->NumberOfDistinctWordForEachParam[$paramName][$ParValue] = $this->NumberOfDistinctWordForEachParam[$paramName][$ParValue] + 1;
                } else {
                    $this->NumberOfDistinctWordForEachParam[$paramName][$ParValue] = 1;
                }
            }

        }
    }

    //first param with distinct value
    public function GetFirstParameterNameWithMinDistinctValues()
    {
        $NameOfParamWithMinDistinctValue = "";
        if (count($this->ParamsName) > 0) {
            $r = array();
            foreach ($this->NumberOfDistinctWordForEachParam as $parName => $ArrayOfDistinctValueForThem) {
                if(count($ArrayOfDistinctValueForThem)>1)
                    $r[$parName] = count($ArrayOfDistinctValueForThem);
            }

            $r1 = array();
            $IsClustered = false;
            foreach ($r as $ParName1 => $ParVal1) {
                $IsClustered = false;
                foreach ($this->ListOfClusteringParamsName as $parName2) {
                    if ($ParName1 === $parName2) {
                        $IsClustered = true;
                    }
                }
                if (!$IsClustered)
                    $r1[$ParName1] = $ParVal1;
            }

            $MinNumberOfDistinctValue = min($r1);

            foreach ($r1 as $ParName => $NumberOfDistinctValue) {
                if ($NumberOfDistinctValue <= $MinNumberOfDistinctValue) {
                    $NameOfParamWithMinDistinctValue = $ParName;
                    break;
                }
            }


            /*$MaxNumberOfDistinctValue=max($r);
            if($MaxNumberOfDistinctValue===$MinNumberOfDistinctValue)
                return $NameOfParamWithMinDistinctValue;*/
        }
        return $NameOfParamWithMinDistinctValue;
    }

    public function ClusterBaseOnFirstParamWithMinDistinctValue()
    {
        $r = array();
        if (count($this->ParamsName) > 0) {
            $ParNameWithMineLastPar = $this->GetFirstParameterNameWithMinDistinctValues();
            if ($ParNameWithMineLastPar !== "") {
                $DistinctValueForParamWithMinNumberOfValue = $this->NumberOfDistinctWordForEachParam[$ParNameWithMineLastPar];

                foreach ($DistinctValueForParamWithMinNumberOfValue as $DistinctValue => $NumberOfIt) {


                    $locp = $this->ListOfClusteringParamsName;
                    $locp[] = $ParNameWithMineLastPar;
                    $d = new DataModelForClustering($this->ParamsName, $this->ClusterLevel, $locp);
                    $d->ClusterLevel = $this->ClusterLevel + 1;
                    //$d->ListOfClusterRecord=$this->GetAllRecordWith($DistinctValue,$ParNameWithMineLastPar);
                    foreach ($this->ListOfClusterRecord as $ClusterRecordObject) {
                        $ClusterRecord = (object)$ClusterRecordObject;

                        if ($ClusterRecord->ListOfParamValues[$ParNameWithMineLastPar] == $DistinctValue) {


                            $d->ListOfClusterRecord[$ClusterRecord->AbstractUrlId] = new ClusterRecord($ClusterRecord->AbstractUrlId, $ClusterRecord->ListOfParamValues, $ClusterRecord->AbstractURL);
                            $d->UpdateNumberOfDistinctValue($ClusterRecord->ListOfParamValues);
                        }
                    }

                    reset($d->ListOfClusterRecord);
                    $first_key = key($d->ListOfClusterRecord);
                    $r[$d->ListOfClusterRecord[$first_key]->AbstractUrlId] = $d;
                }
            }

        } else {
            $r[$this->ListOfClusterRecord[0]->AbstractUrlId] = $this;
        }
        return $r;
    }

    public function GetHashID()
    {
        $r = array();
        foreach ($this->ListOfClusterRecord as $clusterRecordObject) {
            $clusterRecord = (object)$clusterRecordObject;
            $r[] = $clusterRecord->AbstractUrlId;
            //$r[] = $clusterRecord->AbstractURL->GetHashOfBaseUrlAndParamName();
        }

        sort($r);
        $SR = "";
        foreach ($r as $ID) {
            $SR .= $ID;
        }

        return sha1($SR);
    }

    public function GetAbstractUrlAsClusterCenter()
    {
        reset($this->ListOfClusterRecord);
        $first_key = key($this->ListOfClusterRecord);
        $MinUse = $this->ListOfClusterRecord[$first_key]->NumberOFUse;
        foreach ($this->ListOfClusterRecord as $RecordObject) {
            $Record = (object)$RecordObject;
            if ($Record->NumberOFUse < $MinUse)
                $MinUse = $Record->NumberOFUse;
        }

        $ClusterRecord = null;
        foreach ($this->ListOfClusterRecord as $RecordObject) {
            $Record = (object)$RecordObject;
            if ($Record->NumberOFUse <= $MinUse) {
                $Record->NumberOFUse++;
                $ClusterRecord = $Record;
                break;
            }

        }

        $this->NumberOfUsedRecordInThisCluster++;
        return clone $ClusterRecord->AbstractURL;
    }
    function destroy()
    {
        $this->ParamsName=array();
        $this->ListOfClusterRecord=array();
        $this->NumberOfDistinctWordForEachParam=array();
        $this->ListOfClusteringParamsName=array();
    }
}

