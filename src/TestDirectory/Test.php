<?php

require_once "BaseDirectory.php";
require_once $BASEDIROFPROJECT."/TestDataGenerationAlgorithm/configs/VSConfig.php";
echo VSConfig::GetString();

echo stats_rand_gen_normal(5,2);


/**
 * Created by PhpStorm.
 * User: computer
 * Date: 11/16/2016
 * Time: 08:55 PM
 */
//a[]
/*
 function GetNormal($mean, $Std, $step = 1)
{
        list($usec, $sec) = explode(' ', microtime());
        $seed= $sec + $usec * 1000000;

    mt_srand($seed);
    $randval = mt_rand();

    $rand1 = (float)mt_rand() / (float)mt_getrandmax();
    $rand2 = (float)mt_rand() / (float)mt_getrandmax();
    $gaussian_number = sqrt(-2 * log($rand1)) * cos(2 * M_PI * $rand2);
    $random_number = ($gaussian_number * $Std) + $mean;
    $random_number = round($random_number / $step) * $step;
    if ($random_number < ($mean - $Std) || $random_number > ($mean +  $Std)) {
        $random_number = GetNormal($mean, $Std);
    }
    return $random_number;
}

for($i=1;$i<20;$i++)
{
    stats_dens_normal(1,2);
    $randval = mt_rand();
    echo $randval."\n";
}
*/
/*
function UniqueRandomNumbersWithinRange($min, $max, $quantity) {
    $numbers = range($min, $max);
    shuffle($numbers);
    return array_slice($numbers, 0, $quantity);
}

print_r( UniqueRandomNumbersWithinRange(0,25,5) );

*/