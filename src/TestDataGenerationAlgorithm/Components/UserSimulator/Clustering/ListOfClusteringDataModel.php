<?php

/**
 * Created by PhpStorm.
 * User: computer
 * Date: 12/20/2016
 * Time: 12:31 PM
 */
class ListOfClusteringDataModel
{
    public $ListOfClusters=array();//ListOfDataModel
    public $ClustersLevel=1;
    public $ParamsNameList;////UrlParametersList->GetInterface

    public function Cluster($MaxLevelOfClustering)
    {
        $NewListOfDataModel=array();

        foreach($this->ListOfClusters as $DataModelForClusteringObject)
        {
            $DataModelForClustering=(object)$DataModelForClusteringObject;
            if($DataModelForClustering->ClusterLevel < $MaxLevelOfClustering)
            {

                $NewListOfDataModel=array_merge($NewListOfDataModel, $DataModelForClustering->ClusterBaseOnFirstParamWithMinDistinctValue());
            }
        }
        $ret=new ListOfClusteringDataModel();
        $ret->ClustersLevel=$this->ClustersLevel+1;
        $ret->ListOfClusters=$NewListOfDataModel;
        $ret->ParamsNameList=$this->ParamsNameList;
        return $ret;
    }
}