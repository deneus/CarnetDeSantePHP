<?php

namespace HealthChain\modules\classes\Neo;


use NeoPHP\Crypto\Hash;
use Elliptic\EC;
use NeoPHP\RPCRequest;

class Contract
{
    const CONTRACT_HASH = '78d7ee954d56c382a422924b2398aa543917ee9f';
//    const CONTRACT_HASH = 'dc675afc61a7c0f7b3d2682bf6e1d8ed865a0e5f';//TEST tuto
    const METHOD_REGISTER = 'register';
    const METHOD_DELEGATE = 'delegate';
    const TYPE_BYTE_ARRAY = 'ByteArray';

    protected $_neo;
    protected static $_instance;
    protected $_wallet;
    protected $_scriptContract;

    private function __construct($wallet, $mainNet = true)
    {
        /*$this->neo = new \NeoPHP\NeoRPC($mainNet);
        $this->neo->setNode($this->neo->getFastestNode());
        $this->_wallet = $wallet;
        $this->_hashContract = self::CONTRACT_HASH;
        $contractState = $this->neo->getContractState(self::CONTRACT_HASH);
        $this->_scriptContract = $contractState['script'];*/
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
    }

    protected function _generateRawTx($method, $params, $key)
    {
        //TODO: Implement tx generation
        //Generation of the invoke to get the tx. This invoke does not create anything on the blockchain.
        $invoke = RPCRequest::request($this->neo->active_node, "invokescript", [$this->_scriptContract]);
        $tx = $invoke['script'];

        /*if(isset($invoke['stack'][0])){
            $result = $this->_unserializeValue($invoke['stack'][0]);
        }*/
        var_dump($tx);
        $signature = $this->_generateSignature($tx, $key );
        //        $signature = $this->_generateSignature($tx, $this->_wallet->getPrivateKey());
        var_dump($signature);
        return $signature;



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
        $key = $ec->keyFromPrivate(bin2hex($privateKey), 'hex');
        $signature = $key->sign($txHash);
        return $signature;
    }

    /**
     * @param $stack: Result from a RPC call / Contract invocation
     * @return $result: Translated value from the result
     */
    protected function _unserializeValue($stack)
    {
        $result = '';
        switch($stack['type']){
            case self::TYPE_BYTE_ARRAY:
                return hex2bin($stack['value']);
            break;
        }
        return $result;
    }

}