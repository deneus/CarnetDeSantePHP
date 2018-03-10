<?php
require __DIR__ . '/vendor/autoload.php';
use NeoPHP\NeoWallet;
use NeoPHP\NeoPHP;
use HealthChain\modules\classes\Neo;

//$neoPHP = new NeoPHP();
//$wallet = new NeoWallet('L1JQqSX4M1HS9nY3nMHs3w2DPzbMxcLo2dwXfk584jJx7GqnUMCM');
//$user = new classes\User();
//$user->register('test');

//e0584e04ab9a2e1e8ce9a365696995368fcc0e40
//7a62daf2918730475c7e6a300a9bb5a8b5583945
$publicKey = "03aaf81b18163abe8b1c2889a145f8ec02fe0e2140b37d61e4c37677053c9496ee";



$params = array('hash' => /*Neo\Contract::CONTRACT_HASH*/ '73ea8ca5b36d84061780676238c187295151f6f7',
    'NEOaddress' => 'testfinal',
    /*'ipfsMaster' => 'valueIPFS'*/);


/*$test = Neo\NeoAPI::call(\HealthChain\modules\classes\User::NEO_METHOD_STOPDELEGATE, Neo\NeoAPI::METHOD_POST,
    $params);

$test = json_decode($test);
var_dump($test);*/

/*$test = \NeoPHP\RPCRequest::request('http://test5.cityofzion.io:8880', "invokescript", ["72300405a7406179cb52af0dcbf18292e2e039b1d864d2a3603e4195b4a52bdd06ebc3607048948bc9fe203be1f262be38e99851265feda885721c2721e0662"]);

var_dump($test);*/

$neo = new \NeoPHP\NeoRPC(false);
$neo->setNode('http://test5.cityofzion.io:8880');
$storage = $neo->getStorage('73ea8ca5b36d84061780676238c187295151f6f7', bin2hex('testfinal'));
var_dump($storage);
//Neo\Contract::getInstance()

