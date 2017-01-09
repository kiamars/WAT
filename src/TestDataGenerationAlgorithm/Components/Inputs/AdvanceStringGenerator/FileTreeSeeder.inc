<?php
/**
 * Created by PhpStorm.
 * User: Computer
 * Date: 6/24/2016
 * Time: 11:40 PM
 */


require_once 'BaseDirectory.php';
require_once 'ISeededValues.inc';


class FileTreeSeeder extends  ISeededValues
{
    public $IncludedFolderPath="/";
    public $AllFilesFullName=array();

    public function  __construct($baseDir="/",$MinLengthOfResult=0,$MaxLengthOfResult=200)
    {
        $this->IncludedFolderPath=$baseDir;
        $this->MinLengthOfResult = $MinLengthOfResult;
        $this->MaxLengthOfResult = $MaxLengthOfResult;

        $objects = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->IncludedFolderPath), RecursiveIteratorIterator::SELF_FIRST);
        foreach($objects as $name => $object){

            if(!is_dir($name))
            {
                $this->AllFilesFullName[]=$name;
            }
        }
    }


    /**
     * Get One valid seeded value
     * @return mixed
     */
    public function GetOrGenerateValidSeededValue()
    {
        $index=array_rand($this->AllFilesFullName,1);
        return $this->AllFilesFullName[$index];
    }
}