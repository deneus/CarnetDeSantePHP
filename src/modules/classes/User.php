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
        //FIXME: Error due to an unknown constant. Had to intialise this class
        $neoPHP = new NeoPHP();
        $this->_user = false;
        if(isset($_SESSION['user']) && !empty($_SESSION['user'])){
            $this->_user = $_SESSION['user'];
        }
        //TODO: Proper sanitize
        /*if(isset($_POST) && !empty($_POST)){
            $this->_formValues = $this->sanitize($_POST);
        }*/
    }

    /**
     * User login form action.
     * @param $privateKey: User's generated private KEY
     * @param string $passphrase: Optionnal, use a passphrase to login with the generated private KEY
     * @param string $useRealPrivateKey: Real NEO private KEY. Should not be used directly
     */
    public function login($privateKey, $passphrase ='', $useRealPrivateKey = false)
    {
        if($this->_user === false) {
            //TODO: Load NEO wallet with passphrase
            if (empty($passphrase)) {
                $wallet = new NeoWallet($privateKey);
                if ($wallet->getAddress()) {
                    $_SESSION['user'] = $wallet->getWIF();
                    $this->_user = $_SESSION['user'];
                }
             }
        }
        return $this->_user !== false;
    }

    /**
     * Register a new NEO wallet used for the dAPP
     * @return : TODO
     */
    public function register($passphrase)
    {
        $newWallet = new NeoWallet();
        //FIXME: Max execution time. For now no encryption
        //$newWallet->encryptWallet($passphrase);

        return $newWallet->getWIF();
    }

    public function getListDocs()
    {

    }


}