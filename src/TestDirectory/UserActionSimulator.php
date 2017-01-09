<?php

/**
 * Created by PhpStorm.
 * User: Mirzaee
 * Date: 10/24/2016
 * Time: 6:50 PM
 */
class UserActionSimulator
{

}
require_once "BaseDirectory.php";

require_once $BASEDIROFPROJECT."/ExternalLibs/vendor/autoload.php";

$ser = new Services_JSON( SERVICES_JSON_USE_TO_JSON );

class A {
// toJSON should return an associtive array of the properties to serialize
// same standard as JSON.stringify()
    function toJSON() {
        return array( 'a' => 10, 'b'=>20) ;
    }
}
echo $ser->encode(new A());