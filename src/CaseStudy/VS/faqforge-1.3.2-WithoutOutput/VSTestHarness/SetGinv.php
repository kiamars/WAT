<?php
/**
 * Created by PhpStorm.
 * User: computer
 * Date: 11/22/2016
 * Time: 09:50 PM
 */
require_once "BaseDirectory.php";
require_once $BASEDIROFPROJECT."/TestDataGenerationAlgorithm/configs/VSConfig.php";
$filename  = "Ginv.csv";
$inputDir  = "C:\\input";
$outputDir = "C:\\output";
echo VSConfig::$MaxGenerationNumber;
$command = "matlab -wait -nojvm -nodesktop -nodisplay -sd ".$inputDir." -r phpcreatefile('".VSConfig::$GinvDirectoryAndFileName."',".VSConfig::$MaxGenerationNumber.",".VSConfig::$x.")";
 exec($command);
//system($command,$command);
echo "\n The following command was run: ".$command."<br/>";
echo "\n".$filename." was created in ".$outputDir."<br/>";
//VSConfig::$MaxGenerationNumber=VSConfig::$MaxGenerationNumber/2;
print_r(file(VSConfig::$GinvDirectoryAndFileName));