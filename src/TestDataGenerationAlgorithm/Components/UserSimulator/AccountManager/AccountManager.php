<?php
/**
 * Created by PhpStorm.
 * User: computer
 * Date: 11/21/2016
 * Time: 12:14 AM
 */

class AccountManager
{
    public $LoginInterface=array("username"=>"POST","password"=>"POST","page"=>"POST","login"=>"POST");
    public $ArrayOfAccount=array();
    private $NumberOfUse=array();
    public function AddNewAccount($NewAccount)
    {
        $NewAccount="NewAccount";
        $NewAccount.=count($this->ArrayOfAccount);
        $this->ArrayOfAccount[$NewAccount]=$NewAccount;
        $this->NumberOfUse[$NewAccount]=0;
    }
    public function GetValidValueForAccount()
    {
        $NewAccount=array();
        $NewAccount[username]="test";
        $NewAccount[username]="test";
        $NewAccount[username]="test";

        $ret=null;
        if(count($this->ArrayOfAccount)>0)
        {
            $minNumberOfUse=min($this->NumberOfUse);

            $RandomKey=null;
            do{
                $RandomKey=array_rand($this->ArrayOfAccount,1);
            }while($this->NumberOfUse[$RandomKey]>$minNumberOfUse);

            $ret=$this->ArrayOfAccount[$RandomKey];
            $this->NumberOfUse[$RandomKey]++;
        }
        else
        {
            echo "\n in".__FILE__."there is no Account setted";
        }
        return $ret;
    }
}