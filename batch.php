<?php
require __DIR__ . '/vendor/autoload.php';
use NeoPHP\NeoWallet;
use NeoPHP\NeoPHP;
use HealthChain\modules\classes\Neo;

//$neoPHP = new NeoPHP();
//$wallet = new NeoWallet('L1JQqSX4M1HS9nY3nMHs3w2DPzbMxcLo2dwXfk584jJx7GqnUMCM');
//$user = new classes\User();
//$user->register('test');

//$params = array('hash' => Neo\Contract::CONTRACT_HASH, 'key', 'Toto');
//Neo\NeoAPI::call(\HealthChain\modules\classes\User::NEO_METHOD_MASTER, Neo\NeoAPI::METHOD_POST,
//    $params);

$neo = new \NeoPHP\NeoRPC(false);
$neo->setNode($neo->getFastestNode());
//$storage = $neo->getStorage(Neo\Contract::CONTRACT_HASH, bin2hex('toto'));
Neo\Contract::getInstance()
var_dump($storage);