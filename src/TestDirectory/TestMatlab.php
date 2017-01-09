<?php




$filename  = "Ginv".date("Y-m-d-H-i-s")."csv";
$inputDir  = "C:\\input";
$outputDir = "C:\\output";


    $command = "matlab -nojvm -nodesktop -nodisplay -sd ".$inputDir." -r phpcreatefile('".$outputDir."\\".$filename."',10,.01)";
   // exec($command);
    system($command,$command);
    echo "The following command was run: ".$command."<br/>";
    echo $filename." was created in ".$outputDir."<br/>";

print_r(file($outputDir."\\".$filename));
