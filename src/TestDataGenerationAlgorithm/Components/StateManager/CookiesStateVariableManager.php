<?php

/**
 * Created by PhpStorm.
 * User: Mirzaee
 * Date: 10/29/2016
 * Time: 9:35 AM
 */
/*
function startsWith($haystack, $needle)
{
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}

$str = '|apples}';

echo startsWith($str, 'd|a'); //Returns true
echo endsWith($str, '}'); //Returns true
*/

require_once "StateVariable.php";
class CookiesStateVariableManager
{
    private $Cookies=array();

    public $IsStateUpdated=false;

    public function GetCookieInterfaceAndValueFor($URL)
    {
        $result=array();
        foreach($this->Cookies  as $statVariableObject)
        {
            $statVariable=(object)$statVariableObject;
            if($statVariable->IsMustSetForThisURL($URL))
            {
                $result[$statVariable->VarName]=$statVariable->CurrentValue;
            }
        }

        $r="COOKIES[";
        foreach($result as $CookieVarName=>$CookieVarValue)
            {
            $r.="(N:".$CookieVarName;
            $r.=",V:".$CookieVarValue."),";
            }
        $r.="]";
        return $r;
    }

    public  function SetCookiesForURl($URLUnderTest,$ArrayOfCookies)
    {
        $this->Cookies=$ArrayOfCookies;
        $_COOKIE=array();

       foreach($this->Cookies as $statVariableObject)
       {
       $statVariable=(object)$statVariableObject;
       $statVariable->SetIfAppropriate($URLUnderTest);
       }
    }



    public function GetCopyCurrentState()
    {
        $r=array();
        foreach($this->Cookies as $varName=>$statVariable){
            $r[$varName]=clone $statVariable;
        }
        return $r;
    }

    public function add($VarName,$Value,$path,$SendForURLStartWith,$ExpiredTime)
    {
        $id=sha1($VarName.$Value.$path.$SendForURLStartWith);
        $newStateVariable=new StateVariable();
        $newStateVariable->VarName=$VarName;
        $newStateVariable->CurrentValue=$Value;
        $newStateVariable->setSendForUrlsStartWith($SendForURLStartWith);
        $newStateVariable->Path=$path;
        $newStateVariable->ExpiredTime=$ExpiredTime;
        $newStateVariable->AddedTime=time();
        $this->Cookies[$id]=$newStateVariable;
    }
    public function __clone(){
        //clone Input vector object to cloned Object
        foreach($this->Cookies as &$statVariable){
            $statVariable=clone $statVariable;
        }
    }
    function destroy()
    {
        $this->Cookies =array();
    }
}

