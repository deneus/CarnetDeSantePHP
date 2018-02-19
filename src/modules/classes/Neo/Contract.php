<?php

namespace HealthChain\modules\classes\Neo;


class Contract
{
    const CONTRACT_HASH = '0x930505c79fd24411e854e93eec08f71b861731fdeb30217dbd0cbc52adc9dbf0';
    const METHOD_REGISTER = 'register';
    const METHOD_DELEGATE = 'delegate';

    protected $_neo;
    protected $_instance;

    private function __construct()
    {
        $this->neo = new \NeoPHP\NeoRPC(false);
        $this->neo->setNode($this->neo->getFastestNode());
    }

    public function getInstance()
    {
        if($this->_instance === null){
            $this->_instance = new Contract();
        }
        return $this->_instance;
    }

    public function getMaster($publicAdr)
    {
        return $this->neo->getStorage(self::CONTRACT_HASH, bin2hex($publicAdr));
    }

    public function registerMaster($master)
    {
        //TODO: Implement proper trigger to the Smart Contract;
    }

}