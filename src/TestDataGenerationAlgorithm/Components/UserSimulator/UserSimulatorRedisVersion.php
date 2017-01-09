<?php
/**
 * Created by PhpStorm.
 * User: computer
 * Date: 11/11/2016
 * Time: 02:53 PM
 */

require_once "BaseDirectory.php";
require_once $BASEDIROFPROJECT . "/TestDataGenerationAlgorithm/Components/TestSequenceAndTestData/AbstractURl.php";
require_once $BASEDIROFPROJECT . "/TestDataGenerationAlgorithm/Components/UserSimulator/Clustering/CLusterAbstractUrls.php";
require_once $BASEDIROFPROJECT . "/ExternalLibs/vendor/autoload.php";

class UserSimulatorRedisVersion
{
    public $ClustersForEachInterface = array();
    public $TestedAction = array();//urls that are tested

    public $HashIDOfReachedInterfaceAndNumberOfUseThem = array();
    public $ReachedPathAndInterface = array();

    public $clusterAbstractUrlList=null;//madule for clustering

    public $RedisClient=null;
    public $Serializer=null;
    public function __construct()
    {
        $this->TestedAction = array();
        $this->ClustersForEachInterface = array();
        $this->HashIDOfReachedInterfaceAndNumberOfUseThem = array();
        $this->ReachedPathAndInterface = array();
        $this->clusterAbstractUrlList=null;

        $this->clusterAbstractUrlList = new ClusterAbstractUrlList(CommonConfig::$MaxClusterLevelsInClusteringAbstractURL);
        $this->Serializer = new Services_JSON(SERVICES_JSON_USE_TO_JSON);
        $this->RedisClient = new Predis\Client();
        $e=null;
        $this->RedisClient->executeRaw(array("FLUSHALL"),$e);;
    }
    public function Initialise($FilePath, $UrlType, $ListOfParams, State $state)
    {
        $state->RequestSequence[] = $FilePath;

        $AbstractUrl = new AbstractURl($FilePath, $UrlType, $ListOfParams, null);
        $AbstractUrl->setState($state);
        $listOfAbstractURL = new ListOfAbstractUrls();
        $listOfAbstractURL->AddNewAbstractUrl($AbstractUrl);

        $this->UpdateOperateAbleUserActions(array($AbstractUrl->Id => $listOfAbstractURL));
    }

    /**
     * @param AbstractURl $CurrentAbstractURl
     * @param $ClusteredListOfAbstractURLs : Each element is an ListOfAbstractUrl (Abstract URL At first level)
     */
    public function UpdateOperateAbleUserActions($ClusteredListOfAbstractURLs)
    {
        foreach ($ClusteredListOfAbstractURLs as $listOfAbstractURLObject) {

           // print"\nOOOOOOOO".$listOfAbstractURLObject->PathAndInterface."\n";
            $listOfAbstractURL = (object)$listOfAbstractURLObject;
            $hashIdBaseURLAndInterfaceOfFirstLevelCluster = $listOfAbstractURL->HashIDOfBaseURLAndInterFace;

            if (!array_key_exists($hashIdBaseURLAndInterfaceOfFirstLevelCluster, $this->HashIDOfReachedInterfaceAndNumberOfUseThem))
            {
                $this->HashIDOfReachedInterfaceAndNumberOfUseThem[$hashIdBaseURLAndInterfaceOfFirstLevelCluster] = 0;
                $this->ReachedPathAndInterface[$hashIdBaseURLAndInterfaceOfFirstLevelCluster] = $listOfAbstractURL->PathAndInterface;

                $this->ClustersForEachInterface[$hashIdBaseURLAndInterfaceOfFirstLevelCluster] = array();

                $this->clusterAbstractUrlList->GetAbstractUlrIdInEachClusters($listOfAbstractURL);
                $ListOfClusterDataMode =   $this->clusterAbstractUrlList->ListOfClusters;

                foreach ($ListOfClusterDataMode as $ClusterDataMode) {
                    $ClusterID = $ClusterDataMode->GetHashID();
                    if (!array_key_exists($ClusterID, $this->ClustersForEachInterface[$hashIdBaseURLAndInterfaceOfFirstLevelCluster]))
                    {
                        $this->ClustersForEachInterface[$hashIdBaseURLAndInterfaceOfFirstLevelCluster][$ClusterID] = $ClusterID;
                        //$this->RedisClient->set($ClusterID,$this->Serializer->encode($ClusterDataMode));
                        $this->RedisClient->set($ClusterID,serialize($ClusterDataMode));
                    }
                }

            }else
            {
                $this->clusterAbstractUrlList->GetAbstractUlrIdInEachClusters($listOfAbstractURL);
                $ListOfClusterDataMode =   $this->clusterAbstractUrlList->ListOfClusters;
                foreach ($ListOfClusterDataMode as $ClusterDataMode) {
                    $ClusterID = $ClusterDataMode->GetHashID();
                    if (!array_key_exists($ClusterID, $this->ClustersForEachInterface[$hashIdBaseURLAndInterfaceOfFirstLevelCluster]))
                    {
                        $this->ClustersForEachInterface[$hashIdBaseURLAndInterfaceOfFirstLevelCluster][$ClusterID] = $ClusterID;
                        $this->RedisClient->set($ClusterID,serialize($ClusterDataMode));
                        //$this->RedisClient->set($ClusterID,$this->Serializer->encode($ClusterDataMode));
                    }

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

            //retrive from redis
            //  $ClustersInSelectedInterface = $this->ClustersForEachInterface[$SelectedInterfaceID];
            $ClustersInSelectedInterface=array();
            foreach($this->ClustersForEachInterface[$SelectedInterfaceID] as $ClusterID)
            {
                //$ClustersInSelectedInterface[$ClusterID]=$this->Serializer->decode($this->RedisClient->get($ClusterID));
                $ClustersInSelectedInterface[$ClusterID]=unserialize($this->RedisClient->get($ClusterID));

            }
        print"******\n";
         print_r($this->ReachedPathAndInterface[$SelectedInterfaceID]);


            $ClusterWithMinNumberOfUse = null;
            $IDofClusterWithMinNumberOfUse=0;

            reset($ClustersInSelectedInterface);
            $first_key = key($ClustersInSelectedInterface);
            $MinNumberOfUse = $ClustersInSelectedInterface[$first_key]->NumberOfUsedRecordInThisCluster;

            foreach ($ClustersInSelectedInterface as $ID=> $ClusterDataModel) {
                if ($ClusterDataModel->NumberOfUsedRecordInThisCluster <= $MinNumberOfUse) {
                    $MinNumberOfUse = $ClusterDataModel->NumberOfUsedRecordInThisCluster;
                    $ClusterWithMinNumberOfUse = $ClusterDataModel;
                    $IDofClusterWithMinNumberOfUse=$ID;

                }
            }

            $NextUrl = $ClusterWithMinNumberOfUse->GetAbstractUrlAsClusterCenter();
            $this->RedisClient->set($IDofClusterWithMinNumberOfUse,serialize($ClusterWithMinNumberOfUse));

        } while (array_key_exists($NextUrl->Id, $this->TestedAction) && $NextUrl->GetRequestLength() >= CommonConfig::$MaxRequestLength);

        if($NextUrl!=null)
        {

            $this->TestedAction[$NextUrl->Id] = $NextUrl->Id;
        }else
        {
            echo "\n all url is tested or max Request length is reached \n";
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


