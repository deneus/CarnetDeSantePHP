<?php
require __DIR__ . '/vendor/autoload.php';
use NeoPHP\NeoWallet;
use NeoPHP\NeoPHP;
use HealthChain\modules\classes\Neo;

//$neoPHP = new NeoPHP();
//$wallet = new NeoWallet('L1JQqSX4M1HS9nY3nMHs3w2DPzbMxcLo2dwXfk584jJx7GqnUMCM');
//$user = new classes\User();
//$user->register('test');

$params = array('hash' => Neo\Contract::CONTRACT_HASH,
    'key', 'Toto');
$test = Neo\NeoAPI::call(\HealthChain\modules\classes\User::NEO_METHOD_MASTER, Neo\NeoAPI::METHOD_POST,
    $params);

//$test = \NeoPHP\RPCRequest::request('http://test5.cityofzion.io:8880', "invokescript", ["72300405a7406179cb52af0dcbf18292e2e039b1d864d2a3603e4195b4a52bdd06ebc3607048948bc9fe203be1f262be38e99851265feda885721c2721e0662"]);

var_dump($test);

//$neo = new \NeoPHP\NeoRPC(false);
//$neo->setNode($neo->getFastestNode());
//$storage = $neo->getStorage(Neo\Contract::CONTRACT_HASH, bin2hex('maclef'));
//Neo\Contract::getInstance()

