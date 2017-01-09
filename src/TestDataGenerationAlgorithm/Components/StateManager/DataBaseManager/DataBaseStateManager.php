<?php
require_once "BaseDirectory.php";
require_once $BASEDIROFPROJECT . "/TestDataGenerationAlgorithm/configs/CommonConfig.php";
require_once "backup_restore.php";

class DataBaseStateManager
{
    public $DataBaseBackUpDirectory = "";
    protected $BackUpExtensionFileName = ".sql";

    public $LastRestoredBackUpFileName = "";

    protected $db_host;
    protected $db_name;
    protected $db_user;
    protected $db_pass;

    public $DataBaseBackUpReStoreManager = null;

    public function __construct()
    {
        $this->db_host = CommonConfig::$db_host;
        $this->db_name = CommonConfig::$db_name;
        $this->db_user = CommonConfig::$db_user;
        $this->db_pass = CommonConfig::$db_pass;

        $this->DataBaseBackUpDirectory = CommonConfig::GetBackUpDirectory();

        $this->DataBaseBackUpReStoreManager = new backup_restore($this->db_host, $this->db_name, $this->db_user, $this->db_pass);
        //  $this->DataBaseBackUpReStoreManager->path=$this->DataBaseBackUpDirectory;
    }

    protected function WriteBackUpFile($FileName, $Content, $Extension = ".sql")
    {
        //if base directory is not exit create it
        if (!file_exists($this->DataBaseBackUpDirectory)) {
            if (!mkdir($this->DataBaseBackUpDirectory, 0777, true)) {
                die('Failed to create folders....in log manger in specified path (ie.' . $this->DataBaseBackUpDirectory . ")");
            }
        }

        $FullName = $this->DataBaseBackUpDirectory . '/' . $FileName . $Extension;
        while (file_exists($FullName)) {
            $FileName = "1" . $FileName;
            $FullName = $this->DataBaseBackUpDirectory . '/' . $FileName . $Extension;
        }

        $FullName = $this->DataBaseBackUpDirectory . '/' . $FileName . $Extension;

        $backUpFile = fopen($FullName, "w");
        fwrite($backUpFile, $Content);
        fclose($backUpFile);

        return $FileName;
    }

    protected function ReadAllContentOfFillAsArrayOfLine($FileName, $Extension = ".sql")
    {
        $FullName = $this->DataBaseBackUpDirectory . "\\" . $FileName . $Extension;

        $Lines = file($FullName);


        return $Lines;
    }

    protected function ReadAllContentOfBackUp($FileName, $Extension = ".sql")
    {
        $FullName = $this->DataBaseBackUpDirectory . "\\" . $FileName . $Extension;
        $backUpFile = fopen($FullName, "r") or die("Unable to open file!");
        $Lines = fread($backUpFile, filesize($FullName));
        fclose($backUpFile);
        return $Lines;
    }

    public
    function BackUpIfDBStateChanged()
    {
        //get backup
        //if current backup file content is different with

        //CreateFileName
        //writeBackUp
        //return BackupFileName

        $BackUpFileName = $this->db_name . "_" . date("Y-m-d_H-i-s");
        $SQLBackUpContent = $this->DataBaseBackUpReStoreManager->backup();

        if ($this->LastRestoredBackUpFileName != "") //if not done Restoration so for backup
        {
            $LastRunDBBackUpContent = $this->ReadAllContentOfBackUp($this->LastRestoredBackUpFileName);

            if ($SQLBackUpContent == $LastRunDBBackUpContent) {
                return $this->LastRestoredBackUpFileName;
            } else {
                return $this->WriteBackUpFile($BackUpFileName, $SQLBackUpContent, ".sql");
            }

        } else {
            return $this->WriteBackUpFile($BackUpFileName, $SQLBackUpContent, ".sql");
        }
    }

    public function ReStore($BackUpFileName)
    {
        if ($this->LastRestoredBackUpFileName !== $BackUpFileName) {
            $this->LastRestoredBackUpFileName = $BackUpFileName;
            $lines = $this->ReadAllContentOfFillAsArrayOfLine($BackUpFileName);
            $message = $this->DataBaseBackUpReStoreManager->restore($lines);
            return $message;
        }
    }
}



