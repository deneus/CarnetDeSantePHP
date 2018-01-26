<?php

namespace HealthChain\modules\classes;


use HealthChain\modules\traits\PostTrait;
use NeoPHP\NeoWallet;
use NeoPHP\NeoPHP;

class User
{
    use PostTrait;

    private $_formValues;
    private $_user;
    const TYPE_USER_PATIENT= 0;
    const TYPE_USER_DOCTOR= 1;

    public function __construct()
    {
        if(isset($_SESSION['user']) && !empty($_SESSION['user'])){
            $this->_user = $_SESSION['user'];
        }
        if(isset($_POST) && !empty($_POST)){
            $this->_formValues = $this->sanitize($_POST);
        }
    }

    /**
     * User login form action.
     * @param $privateKey: User's generated private KEY
     * @param string $passphrase: Optionnal, use a passphrase to login with the generated private KEY
     * @param string $useRealPrivateKey: Real NEO private KEY. Should not be used directly
     */
    public function login($privateKey, $passphrase ='', $useRealPrivateKey = false)
    {
        //TODO: Load NEO wallet with Public Key
        if(empty($passphrase)){

        }
    }

    /**
     * Register a new NEO wallet used for the dAPP
     * @return : TODO
     */
    public function register()
    {
        //FIXME: Error due to an unknown constant. Had to intialise this class
        $neoPHP = new NeoPHP();
        $newWallet = new NeoWallet();
        $walletValues = ['NEP2' => $newWallet->isNEP2() /*, 'encryptedKey' => $newWallet->getEncryptedKey()*/,
            'WIF' => $newWallet->getWIF(), 'Address' => $newWallet->getAddress(), 'PrivateKEY' => $newWallet->getPrivateKey(),
            'PublicKey' => $newWallet->getPublicKey()];


        var_dump($walletValues);
        var_dump($newWallet);
    }

    public function getListDocs()
    {

    }


}