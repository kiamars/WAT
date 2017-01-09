<?php
/**
 * Created by PhpStorm.
 * User: Mirzaee
 * Date: 10/29/2016
 * Time: 3:35 PM
 */

class State
{
    public $Cookies=null;//array of object
    public $Session=null;//
    public $DataBaseFileName="";

    //index.php?name=va1
    public $RequestSequence=array();

    public function __clone(){
        //clone Input vector object to cloned Object
        if(is_array($this->Cookies))
            foreach($this->Cookies as &$CV){
            $CV=clone $CV;
        }

        if(is_array($this->Session))
        foreach($this->Session as &$SV){
            if(is_object($SV))
                $SV=clone $SV;
        }
    }


    public function GetHashID()
    {
        $r="";
        foreach($this->Cookies as $CObject)
        {
            $r.=$CObject->GetHashID();
        }
        foreach($this->Session as $SVarName=>$SVarValue)
        {
            $r.=$SVarName.$SVarValue;
        }
      //  $r.=$this->DataBaseFileName;
      /*  foreach($this->RequestSequence as $request)
        {
            $r.=$request;
        }*/
        return sha1($r);
    }

    public function GetString()
    {
        $r="RequestSequence:[";
        foreach($this->RequestSequence  as $Request)
            $r.=$Request."\n";
        $r.="]DataBaseFileName:".$this->DataBaseFileName;
        return $r;
    }
    function destroy()
    {
        $this->Cookies =array();
        $this->Session =array();
        $this->RequestSequence =array();
    }
}

