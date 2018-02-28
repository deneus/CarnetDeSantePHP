<?php
require __DIR__ . '/vendor/autoload.php';
use NeoPHP\NeoWallet;
use NeoPHP\NeoPHP;
use HealthChain\modules\classes\Neo;

//$neoPHP = new NeoPHP();
//$wallet = new NeoWallet('L1JQqSX4M1HS9nY3nMHs3w2DPzbMxcLo2dwXfk584jJx7GqnUMCM');
$contract = Neo\Contract::getInstance('L1JQqSX4M1HS9nY3nMHs3w2DPzbMxcLo2dwXfk584jJx7GqnUMCM', false);
$signature = $contract->registerMaster('master');