<?php
/**
 * Created by PhpStorm.
 * User: Mirzaee
 * Date: 10/27/2016
 * Time: 4:33 PM
 */
require_once "BaseDirectory.php";
require_once $BASEDIROFPROJECT . "/TestDataGenerationAlgorithm/Components/TestSequenceAndTestData/ListOfAbstractUrls.php";
require_once "DataModelForClustering.php";
require_once "ListOfClusteringDataModel.php";

Class ClusterAbstractUrlList{

    public $ClusterListManager=null;
    public $MaxLevelOfCluster=2;

    public $ListOfClusters=array();//Keep Final List Of Clustered Data Model

    public function __construct($MaxLevelOfCluster)
    {
        $this->MaxLevelOfCluster=$MaxLevelOfCluster;
    }

    public function CreateModel(ListOfAbstractUrls $AbstractUrlLists)
    {
        $this->ClusterListManager=new ListOfClusteringDataModel();
        $this->ClusterListManager->ClustersLevel=1;
        $this->ClusterListManager->ParamsNameList=$AbstractUrlLists->ParamsNameList;

        $dataModelForClustering=new DataModelForClustering($this->ClusterListManager->ParamsNameList,1,array());

        foreach($AbstractUrlLists->UrlList as $AbstractUlrObj)
        {
            $AbstractUlr=(object)$AbstractUlrObj;
            $dataModelForClustering->AddNewRecord($AbstractUlr);
        }

        reset($dataModelForClustering->ListOfClusterRecord);
        $clusterId = key($dataModelForClustering->ListOfClusterRecord);//firstKey In
        $this->ClusterListManager->ListOfClusters[$clusterId]=$dataModelForClustering;
    }



    public function GetAbstractUlrIdInEachClusters(ListOfAbstractUrls $AbstractUrlLists)
    {
        $this->CreateModel($AbstractUrlLists);

       //max level of clustering is max(amxLevel,maxNumberOfParam)
        if($this->MaxLevelOfCluster>count($this->ClusterListManager->ParamsNameList))
            $this->MaxLevelOfCluster=count($this->ClusterListManager->ParamsNameList);

        if(count($AbstractUrlLists->UrlList)>1)
            $this->ListOfClusters=$this->ClusterUrls();
        else if(count($AbstractUrlLists->UrlList)===1)
        {
            $this->ListOfClusters=$this->ClusterListManager->ListOfClusters;
        }
    }

    public function ClusterUrls()
    {
        while($this->ClusterListManager->ClustersLevel <$this->MaxLevelOfCluster)
        {
            $this->ClusterListManager=$this->ClusterListManager->Cluster($this->MaxLevelOfCluster);
        }

      return $this->ClusterListManager->ListOfClusters;
    }

    function destroy()
    {
        $this->ListOfClusters=array();
    }
}
