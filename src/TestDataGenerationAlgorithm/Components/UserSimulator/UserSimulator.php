<?php

/**
 * Created by PhpStorm.
 * User: Mirzaee
 * Date: 10/26/2016
 * Time: 4:23 PM
 */
require_once "BaseDirectory.php";
require_once $BASEDIROFPROJECT . "/TestDataGenerationAlgorithm/Components/TestSequenceAndTestData/AbstractURl.php";
require_once $BASEDIROFPROJECT . "/TestDataGenerationAlgorithm/Components/UserSimulator/Clustering/CLusterAbstractUrls.php";


class UserSimulator
{
    public $ClustersForEachInterface = array();

    public $TestedAction = array();//urls that are tested

    public $HashIDOfReachedInterfaceAndNumberOfUseThem = array();
    public $ReachedPathAndInterface = array();

    public $clusterAbstractUrlList=null;//madule for clustering

    public function __construct()
    {
        $this->clusterAbstractUrlList = new ClusterAbstractUrlList(CommonConfig::$MaxClusterLevelsInClusteringAbstractURL);
    }
    public function Initialise($FilePath, $UrlType, $ListOfParams, State $state)
    {
        $state->RequestSequence[] = $FilePath;

        $AbstractUrl = new AbstractURl($FilePath, $UrlType, $ListOfParams, null);
        $AbstractUrl->setState($state);
        $listOfAbstractURL = new ListOfAbstractUrls();
        $listOfAbstractURL->AddNewAbstractUrl($AbstractUrl);

        $this->UpdateOperateAbleUserActions($AbstractUrl, array($AbstractUrl->Id => $listOfAbstractURL));
    }

    /**
     * @param AbstractURl $CurrentAbstractURl
     * @param $ClusteredListOfAbstractURLs : Each element is an ListOfAbstractUrl (Abstract URL At first level)
     */
    public function UpdateOperateAbleUserActions(AbstractURl $CurrentAbstractURl, $ClusteredListOfAbstractURLs)
    {
        foreach ($ClusteredListOfAbstractURLs as $listOfAbstractURLObject) {
            $listOfAbstractURL = (object)$listOfAbstractURLObject;
            $hashIdBaseURLAndInterfaceOfFirstLevelCluster = $listOfAbstractURL->HashIDOfBaseURLAndInterFace;

            if (!array_key_exists($hashIdBaseURLAndInterfaceOfFirstLevelCluster, $this->HashIDOfReachedInterfaceAndNumberOfUseThem)) {

                $this->HashIDOfReachedInterfaceAndNumberOfUseThem[$hashIdBaseURLAndInterfaceOfFirstLevelCluster] = 0;
                $this->ReachedPathAndInterface[$hashIdBaseURLAndInterfaceOfFirstLevelCluster] = $listOfAbstractURL->PathAndInterface;

                $this->ClustersForEachInterface[$hashIdBaseURLAndInterfaceOfFirstLevelCluster] = array();
                $this->clusterAbstractUrlList->GetAbstractUlrIdInEachClusters($listOfAbstractURLObject);
                $ListOfClusterDataMode =   $this->clusterAbstractUrlList->ListOfClusters;
                foreach ($ListOfClusterDataMode as $ClusterDataMode) {
                    $ClusterID = $ClusterDataMode->GetHashID();
                    if (!array_key_exists($ClusterID, $this->ClustersForEachInterface[$hashIdBaseURLAndInterfaceOfFirstLevelCluster]))
                        $this->ClustersForEachInterface[$hashIdBaseURLAndInterfaceOfFirstLevelCluster][$ClusterID] = $ClusterDataMode;
                }
            } else {
                $this->clusterAbstractUrlList->GetAbstractUlrIdInEachClusters($listOfAbstractURLObject);
                $ListOfClusterDataMode =   $this->clusterAbstractUrlList->ListOfClusters;
                foreach ($ListOfClusterDataMode as $ClusterDataMode) {
                    $ClusterID = $ClusterDataMode->GetHashID();
                    if (!array_key_exists($ClusterID, $this->ClustersForEachInterface[$hashIdBaseURLAndInterfaceOfFirstLevelCluster]))
                        $this->ClustersForEachInterface[$hashIdBaseURLAndInterfaceOfFirstLevelCluster][$ClusterID] = $ClusterDataMode;
                }
            }
        }
    }


    public function GetNextAbstractUrl()
    {
        $NextUrl = null;
        do {

            $MinValueOFInterfaceUses = min($this->HashIDOfReachedInterfaceAndNumberOfUseThem);

            $HashIDOfInterfaceWithMinNumberOfUse=array();
            foreach ($this->HashIDOfReachedInterfaceAndNumberOfUseThem as $HashId => $NumberOfUse) {
                if ($NumberOfUse <= $MinValueOFInterfaceUses) {
                    $HashIDOfInterfaceWithMinNumberOfUse[$HashId]=$NumberOfUse;
                }
            }

            $SelectedInterfaceID = 0;//Selected
            //Select form them randomly
            $SelectedInterfaceID= array_rand($HashIDOfInterfaceWithMinNumberOfUse,1);


            $this->HashIDOfReachedInterfaceAndNumberOfUseThem[$SelectedInterfaceID]= $this->HashIDOfReachedInterfaceAndNumberOfUseThem[$SelectedInterfaceID]+1;
            $ClustersInSelectedInterface = $this->ClustersForEachInterface[$SelectedInterfaceID];
            $ClusterWithMinNumberOfUse = null;

            reset($ClustersInSelectedInterface);
            $first_key = key($ClustersInSelectedInterface);
            $MinNumberOfUse = $ClustersInSelectedInterface[$first_key]->NumberOfUsedRecordInThisCluster;

            foreach ($ClustersInSelectedInterface as $ClusterDataModel) {
                if ($ClusterDataModel->NumberOfUsedRecordInThisCluster <= $MinNumberOfUse) {
                    $MinNumberOfUse = $ClusterDataModel->NumberOfUsedRecordInThisCluster;
                    $ClusterWithMinNumberOfUse = $ClusterDataModel;
                }
            }

            $NextUrl = $ClusterWithMinNumberOfUse->GetAbstractUrlAsClusterCenter();
        } while (array_key_exists($NextUrl->Id, $this->TestedAction) && $NextUrl->GetRequestLength() >= CommonConfig::$MaxRequestLength);

        if($NextUrl!=null)
        {
         //   $this->HashIDOfReachedInterfaceAndNumberOfUseThem[]
            $this->TestedAction[$NextUrl->Id] = $NextUrl->Id;
        }
        return $NextUrl;
    }

    public function GetReachedURLsAndNumberOfUseThem()
    {
        $r = array();
        foreach ($this->HashIDOfReachedInterfaceAndNumberOfUseThem as $InterfaceHashId => $NumberOfUse) {
            $r[] = "[" . $this->ReachedPathAndInterface[$InterfaceHashId] . "," . $NumberOfUse . "]";
        }
        return $r;
    }
}


