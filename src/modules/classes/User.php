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
    public function register($passphrase)
    {
        //FIXME: Error due to an unknown constant. Had to intialise this class
        $neoPHP = new NeoPHP();
        $newWallet = new NeoWallet();
        //FIXME: Max execution time. For now no encryption
        //$newWallet->encryptWallet($passphrase);

        $html = '<p> Your login is your unique identifier</p>';
        $html .= '<p> Your backup code is used if you lost your password.';
        $html .= '<br /> Those informations are private and should never be shared with everyone.';
        $html .= '<br /> We will never ask you such information by email or in the phone.';
        $html .= '<br /> Please save carefully the following information.';
        $html .= '<ul>';
       // $html .= '<li>Your Login: ' .$newWallet->getEncryptedKey() .'</li>';
        $html .= '<li>Your Login: ' . $newWallet->getWIF() . '</li>';
        return $html;
    }

    public function getListDocs()
    {

    }


}