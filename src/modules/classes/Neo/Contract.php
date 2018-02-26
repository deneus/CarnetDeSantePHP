<?php

namespace HealthChain\modules\classes\Neo;


use NeoPHP\Crypto\Hash;
use Elliptic\EC;

class Contract
{
    const CONTRACT_HASH = '0x930505c79fd24411e854e93eec08f71b861731fdeb30217dbd0cbc52adc9dbf0';
    const METHOD_REGISTER = 'register';
    const METHOD_DELEGATE = 'delegate';

    protected $_neo;
    protected static $_instance;
    protected $_wallet;

    private function __construct($wallet, $mainNet = true)
    {
        $this->neo = new \NeoPHP\NeoRPC($mainNet);
        $this->neo->setNode($this->neo->getFastestNode());
        $this->_wallet = $wallet;
    }

    public static function getInstance($wallet, $mainNet = true)
    {
        if(self::$_instance === null){
            self::$_instance = new Contract($wallet, $mainNet);
        }
        return self::$_instance;
    }

    public function getMaster($publicAdr)
    {
        return $this->neo->getStorage(self::CONTRACT_HASH, bin2hex($publicAdr));
    }

    public function registerMaster($master)
    {

        $rawTransaction = $this->_generateRawTx(self::METHOD_REGISTER, ['master' => 'masterHash'], $this->_wallet);
        return $rawTransaction;
        //$rawTransaction = $this->_ge
//        $this->neo->sendRawTransaction();
        //TODO: Implement proper trigger to the Smart Contract;
    }

    protected function _generateRawTx($method, $params, $key)
    {
        //TODO: Implement tx generation
        $tx = '';

        $signature = $this->_generateSignature($tx, $key );
        return $signature;

//        $signature = $this->_generateSignature($tx, $this->_wallet->getPrivateKey());

        $rawTransaction = '';

        return $rawTransaction;
    }

    /**
     * generate a signature of the transaction based on a given private key
     * @param $tx : Serialized unsigned transaction
     * @param $privateKey: Private Key
     * @return $signature: Signature - does not include the tx
     */
    protected function _generateSignature($tx, $privateKey)
    {
        $ec = new EC('secp256k1');
        $txHash = bin2hex(Hash::SHA256($tx));
        var_dump($privateKey);
        $key = $ec->keyFromPrivate(bin2hex($privateKey));
        var_dump($key);die();
        $signature = bin2hex($ec->sign($txHash, $key));
        return $signature;
    }

}