<?php
/**
 * Created by PhpStorm.
 * User: computer
 * Date: 11/11/2016
 * Time: 02:17 PM
 */

class a{
    public $i=5;
    public $b=array();
}
$r=new a();
$r->i=5;
$r->b=array("3","agafg","agfg");

//require 'Predis/Autoloader.php';
require_once"BaseDirectory.php";
require_once $BASEDIROFPROJECT . "/ExternalLibs/vendor/autoload.php";
Predis\Autoloader::register();

$client = new Predis\Client();
$client->set('foo',"dfhyghjgfh");

$e=null;
$client->executeRaw(array("FLUSHALL"),$e);
echo $e;
print_r($client->get('foo'));
/*
// Define a new command by extending Predis\Command\Command:
class BrandNewRedisCommand extends Predis\Command\Command
{
    public function getId()
    {
        return 'NEWCMD';
    }
}

// Inject your command in the current profile:
$client = new Predis\Client();
$client->getProfile()->defineCommand('newcmd', 'BrandNewRedisCommand');

echo $response = $client->newcmd();
/*
/*
// Parameters passed using a named array:
$client = new Predis\Client([
    'scheme' => 'tcp',
    'host'   => '10.0.0.1',
    'port'   => 6379,
]);*/

