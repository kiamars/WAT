<?php

/**
 * Created by PhpStorm.
 * User: Mirzaee
 * Date: 10/29/2016
 * Time: 9:44 AM
 */
require_once "BaseDirectory.php";
require_once $BASEDIROFPROJECT."/TestDataGenerationAlgorithm/Components/StateManager/DataBaseManager/DataBaseStateManager.php";
require_once"CookiesStateVariableManager.php";
require_once "State.php";

class StateManager
{
    public $CookiesStateManger = null;//CookiesStateManager
    public $DataBaseManager =null;
    protected $SESSION = array();
    public $RequestSequence=array();

    public $PathOfPageUnderTest;

    //public $FormFields = array();
    //public $QueryString=array();

    public function __construct()
    {
        $this->CookiesStateManger = new CookiesStateVariableManager();

        $this->SESSION = array();
        $this->DataBaseManager = new DataBaseStateManager();

        $this->RequestSequence=array();

       // $this->FormFields = new StateVariableManager("P");
       //  $this->QueryString=new StateVariableManager("G");

    }

    public function  GetCurrentState()
    {
        $s=new State();
        $s->Cookies=$this->CookiesStateManger->GetCopyCurrentState();

        $s->Session=array();
        foreach($_SESSION as $SK=>$SV)
        {
            if(is_object($SV))
                $s->Session[$SK]=clone $SV;
            else
            $s->Session[$SK]=$SV;
        }
        $s->DataBaseFileName=$this->DataBaseManager->BackUpIfDBStateChanged();

        $s->RequestSequence=$this->RequestSequence;


        return $s;
    }

    public function  SetCurrentState(State $InitialState)
    {
        if($InitialState!=null) {

            $this->RequestSequence=$InitialState->RequestSequence;

            $this->CookiesStateManger->SetCookiesForURl($this->PathOfPageUnderTest,$InitialState->Cookies);

            $_SESSION = array();
            foreach ($InitialState->Session as $SK => $SV) {
                $_SESSION[$SK] = $SV;
            }

            if($InitialState->DataBaseFileName!="")
                $this->DataBaseManager->ReStore($InitialState->DataBaseFileName);
        }
    }

    public function AddNewResourceToResourceSequence($RequestFileName,$ParamsNameTypeValue)
    {
        $this->RequestSequence[]=$RequestFileName.$ParamsNameTypeValue.$this->CookiesStateManger->GetCookieInterfaceAndValueFor($RequestFileName);
    }

    public function SetCookiesIfExistForURLStat(AbstractURl $abstractURl)
    {
        $this->CookiesStateManger->SetCookiesForURl($abstractURl->BaseURL,$abstractURl);
    }

    function destroy()
    {
        $this->CookiesStateManger =null;
        $this->DataBaseManager=null;
        $this->SESSION=array();
        $this->RequestSequence=array();
    }
}

/*
$Ini=new StateManager();
$Ini->AddNewResourceToResourceSequence("Index.php");
$Ini->AddNewResourceToResourceSequence("home.php?name==ali");

$Ini->CookiesStateManger->add("d",curentvalu,"10s",10,10);

session_start();
$_SESSION["name"]="ali";

$CopyState=$Ini->GetCurrentState();
//print_r($Ini->GetCurrentState());
$Ini->SetCurrentState($CopyState);


session_start();
$_SESSION["ali"]=14255;

$_COOKIE=array();

print $_SESSION["ali"];

*/
