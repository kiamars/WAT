<?php

/**
 * Created by PhpStorm.
 * User: Mirzaee
 * Date: 10/26/2016
 * Time: 3:29 PM
 */

interface IReSolver
{
    public function Resolve($PageFilePath);
}
/*
class PageObjectResolver
{

    public $listOFObject=array();
    public function Register($PathAndFileName,$ObjectOfThatPage)
    {
        $IsExist=false;
        foreach($this->listOFObject as $key=>$Value)
        {
            if($key===$PathAndFileName)
                $IsExist=true;
        }
        if(!$IsExist)
        {
            $this->listOFObject[$PathAndFileName]=$ObjectOfThatPage;
        }
    }

    public function InitialiseAllPage()
    {
        foreach($this->listOFObject as $PageObject)
        {
            $Page=(object)$PageObject;
            $Page->InitializeInputListType();
        }

    }

    public function Resolve($PathAndFileName)
    {
        $ret=null;
        foreach($this->listOFObject as $key=>$Value)
        {
            if($key===$PathAndFileName)
                $ret=$Value;
        }
        if($ret===null)
            print"\nNot page Founded for".$PathAndFileName." In PageObject RESOLVER(".__FILE__.__LINE__.")" ;
        return $ret;
    }
}
*/
/*
class a
{
    public $i=4;
}
class b{
    public $i=5;
}

$t=new PageObjectResolver();
$t->Register("ali",new a());
$t->Register("ali",new a());

$t->Register("b",new b());
$a=(object)$t->Resolve("ali");
echo $a->i;*/