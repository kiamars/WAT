<?php
/**
 * Created by PhpStorm.
 * User: computer
 * Date: 11/11/2016
 * Time: 10:42 AM
 */

require_once "BaseDirectory.php";
require_once $BASEDIROFPROJECT . "/TestDataGenerationAlgorithm/IPageObjectResolver.php";
class PageResolver implements IReSolver
{
    protected $RegisteredClass=array();
    public function Register($PageFilePath)
    {
        $this->RegisteredClass[$PageFilePath]=$PageFilePath;
    }

    public function Resolve($PageFilePath)
    {
        //check if exist
        $ClassName ="";
        if(array_key_exists($PageFilePath,$this->RegisteredClass))
            $ClassName=$this->RegisteredClass[$PageFilePath];

        $PageObject = null;
        switch ($ClassName) {
            case FilePath::Admin_Index_php:
                $PageObject = new Admin_Index_php();
                $PageObject->InitializeInputListType();
                break;
            case FilePath::Index_php:
                $PageObject = new Index_php();
                $PageObject->InitializeInputListType();
                break;
            case FilePath::Admin_LogIn_php:
                $PageObject = new Admin_LogIn_php();
                $PageObject->InitializeInputListType();
                break;
            case FilePath::Admin_LogOut_php:
                $PageObject = new Admin_LogOut_php();
                $PageObject->InitializeInputListType();
                break;
        }
        if($PageObject==null)
            print"\nNot calss registerd for".$PageFilePath." In PageObject RESOLVER(".__FILE__.__LINE__.")" ;
        return $PageObject;
    }
}