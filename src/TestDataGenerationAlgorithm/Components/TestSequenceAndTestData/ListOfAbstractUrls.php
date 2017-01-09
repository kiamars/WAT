<?php
require_once "AbstractURl.php";
class ListOfAbstractUrls
{
    public $UrlList;

    public $HashIDOfBaseURLAndInterFace="";
    public $PathAndInterface="";

    public $ParamsNameList;
    public function AddNewAbstractUrl(AbstractURl $abstractURl)
    {
        if(!array_key_exists($abstractURl->Id, $this->UrlList))
        {
            //for prevent of copy abstract URL
            $this->UrlList[$abstractURl->Id]=$abstractURl;

            $this->PathAndInterface=$abstractURl->GetPathAndInterface();
            $this->HashIDOfBaseURLAndInterFace=$abstractURl->GetHashOfBaseUrlAndParamName();
            $this->ParamsNameList=$abstractURl->ParameterListManager->GetInterface();
        }
    }
    function destroy()
    {
        $this->UrlList = array();
    }
/*
 * muse be cleared
    public function AddAbstractURLFrom($FilePath,$UrlType,$ListOfParams,$state)
    {
        $r=new AbstractURl($FilePath,$UrlType,$ListOfParams,$state);
        $this->UrlList[]=$r;
    }*/
}

