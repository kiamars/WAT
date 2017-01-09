<?php

require_once "BaseDirectory.php";
require_once $BASEDIROFPROJECT."/ExternalLibs/vendor/autoload.php";
class Logger
{
    public $Extension=".csv";
    public $RootDirectory="C:\ResultOfTDG_ForPHPProgram";
    public $FileName="TDG";
    private $FullName;
    public $CSVFile=null;

    public function  __construct($RootDirectory,$FileName,$Extension=".csv")
    {
        $this->RootDirectory=$RootDirectory;
        $this->Extension=$Extension;
//if base directory is not exit create it
        if (!file_exists($this->RootDirectory))
        {
            if (!mkdir($this->RootDirectory, 0777, true)) {
                die('Failed to create folders....in log manger in specified path (ie.'.$this->RootDirectory.")");
            }
        }

        $this->FileName=$FileName;
//        $this->FullName=$this->RootDirectory.'\\'.$this->FileName.$this->Extension;
//        while(file_exists($this->FullName))
//        {
//            $this->FileName= "1".$this->FileName;
//            $this->FullName=$this->RootDirectory.'\\'.$this->FileName.$this->Extension;
//        }
        $this->FullName=$this->RootDirectory.'\\'.$this->FileName.$this->Extension;

       // $this->CSVFile= fopen($this->FullName,"w");
    }


    public function WriteResult($NewLine)
    {
        $this->CSVFile= fopen($this->FullName,"a");
        fwrite($this->CSVFile, $NewLine);
        fclose($this->CSVFile);
    }

    public  function Close()
    {
        fclose($this->CSVFile);
    }
}
/*
$l=new Logger("C:\ResultOfTDG_ForPHPProgram","testgg");
$l->WriteResult("sdvsd;dvdv\n");

$l->WriteResult("s2dvsd;dvdv\n");

$l->WriteResult("3sdvsd;dvdv\n");

$l->WriteResult("5sdvsd;dvdv\n");
*/