<?php
/**
 * Created by PhpStorm.
 * User: computer
 * Date: 11/11/2016
 * Time: 05:02 PM
 */


require_once"BaseDirectory.php";
require_once $BASEDIROFPROJECT . "/ExternalLibs/vendor/autoload.php";

   // create a new instance of Services_JSON
    $json = new Services_JSON(SERVICES_JSON_USE_TO_JSON);
    // convert a complexe value to JSON notation, and send it to the browser
  $value = array('foo', 'bar', array(1, 2, 'baz'), array(3, array(4)));
    $output = $json->encode($value);

print($output); // prints: ["foo","bar",[1,2,"baz"],[3,[4]]]
$input=$output;
    // accept incoming POST data, assumed to be in JSON notation
   $input = file_get_contents('php://input', 1000000);
  $value = $json->decode($input);
print_r($value);