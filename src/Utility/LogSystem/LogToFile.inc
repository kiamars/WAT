<?php
require_once "BaseDirectory.php";
require_once $BASEDIROFPROJECT."/ExternalLibs/vendor/autoload.php";
class LogToFile
{
    public $Extension=".csv";
    public $Separator=",";
    public $RootDirectory="C:\ResultOfTDG_ForPHPProgram";
    public $FileName="TDG";

    private $FullName;
    public $CSVFile=null;

    public function  __construct($RootDirectory,$FileName,$Extension)
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
        $this->FullName=$this->RootDirectory.'\\'.$this->FileName.$this->Extension;
        while(file_exists($this->FullName))
        {
            $this->FileName= "1".$this->FileName;
            $this->FullName=$this->RootDirectory.'\\'.$this->FileName.$this->Extension;
        }
        $this->FullName=$this->RootDirectory.'\\'.$this->FileName.$this->Extension;

        $this->CSVFile= fopen($this->FullName,"w");
    }

    public function WriteObject($Object)
    {
        $ser = new Services_JSON( SERVICES_JSON_USE_TO_JSON );
        $text=$ser->encode($Object);
        $this->WriteResult($text);
    }

    public function WriteResult($ResultJson)
    {
        fwrite($this->CSVFile, $ResultJson);
        fclose($this->CSVFile);
    }

//the close function in this mode mus be clalle after writing
    public  function  WriteALineCSVFile($line)
    {
        fputcsv($this->CSVFile,$line,$this->Separator);
    }

    public  function  WriteArrayOfLinesCSVFile($lines)
    {
        //$file = fopen("contacts.csv","w");
        foreach ($lines as $line)
        {
        fputcsv($this->CSVFile,$line);
        }
        fclose($this->CSVFile);
    }
    public  function Close()
    {
        fclose($this->CSVFile);
    }
}

/*
$fileName='\xampp\htdocs\GenerateTestSequenceForWebApplication\TestDataGenerationAlgorithm\Components\StateManager\DataBaseManager\DataBaseBackUpDirectoryFiles'."\\".'faqforge_2016-10-29_13-14-31.sql';

$finalbackup = file_get_contents($fileName);
unlink($fileName);
print_r($finalbackup);*/


/*
 *
 *     //$size = filesize($f);
          //      echo $f . " is " . $size . " bytes."
 *
 r 	Open a file for read only. File pointer starts at the beginning of the file
w 	Open a file for write only. Erases the contents of the file or creates a new file if it doesn't exist. File pointer starts at the beginning of the file
a 	Open a file for write only. The existing data in file is preserved. File pointer starts at the end of the file. Creates a new file if the file doesn't exist
x 	Creates a new file for write only. Returns FALSE and an error if file already exists
r+ 	Open a file for read/write. File pointer starts at the beginning of the file
w+ 	Open a file for read/write. Erases the contents of the file or creates a new file if it doesn't exist. File pointer starts at the beginning of the file
a+ 	Open a file for read/write. The existing data in file is preserved. File pointer starts at the end of the file. Creates a new file if the file doesn't exist
x+ 	Creates a new file for read/write. Returns FALSE and an error if file already exists
 *
 *
 */