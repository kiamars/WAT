<?php

/**
 * Created by PhpStorm.
 * User: Mirzaee
 * Date: 10/29/2016
 * Time: 9:33 AM
 */

class StateVariable
{
    public $VarName;
    public $CurrentValue;
    private $SendForUrlsStartWith="";
    public $Path=null;
    public $ExpiredTime=0;
    public $AddedTime=0;

    public function SetIfAppropriate($PageUrlUnderTest)
    {
        if($this->IsMustSetForThisURL($PageUrlUnderTest))
        {
            if($this->ExpiredTime < time())
                $_COOKIE[$this->VarName]=$this->CurrentValue;
        }
    }

    public function IsMustSetForThisURL($URL)
    {
        $result=false;
        //abstract url start with
        $PathPart=preg_split("/\//",$URL);
        $UrlStartWith="";
        for($i=0;$i<count($PathPart)-1;$i++)
        {
            $UrlStartWith.=$PathPart[$i];
            $UrlStartWith.="/";
        }

        $length = strlen($this->SendForUrlsStartWith);
        $result= (substr($UrlStartWith, 0, $length) === $this->SendForUrlsStartWith);

        return $result;
    }
    public function GetHashID()
    {
        $value="";
        if(is_array($this->CurrentValue))
        {
            foreach($this->CurrentValue as $v)
            {
                $value.=$v;
            }
        }else
        {
            $value=$this->CurrentValue;
        }

        return(sha1($this->VarName,$value));
    }

    public  function GetString()
    {
        $result="";

        $result.=$this->VarName;
        $result.=$this->CurrentValue;

        $result.=$this->SendForUrlsStartWith;//file that state var used
        $result.=$this->Path;// the file that value given for State variable
        $result.=$this->ExpiredTime=0;
        return $result;
    }

    /**
     * @param string $SendForUrlsStartWith
     */
    public function setSendForUrlsStartWith($SendForUrlsStartWith)
    {
        $PathPart=preg_split("/\//",$SendForUrlsStartWith);
        $UrlStartWith="";
        for($i=0;$i<count($PathPart)-1;$i++)
        {
            $UrlStartWith.=$PathPart[$i];
            $UrlStartWith.="/";
        }

        $this->SendForUrlsStartWith = $UrlStartWith;
    }
}
/*
$s=new StateVariable();
$s->setSendForUrlsStartWith("/index.php");
if($s->IsMustSetForThisURL("/admin/ul/index.php"))
    print "ok";*/