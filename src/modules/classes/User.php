<?php

namespace HealthChain\modules\classes;


use HealthChain\modules\traits\PostTrait;
use NeoPHP\NeoWallet;
use NeoPHP\NeoPHP;

class User
{
    use PostTrait;

    const TYPE_USER_PATIENT= 0;
    const TYPE_USER_DOCTOR= 1;

    private $_user;
    protected $ipfs;
    public $fullName;
    public $email;
    public $dob;
    public $type;
    public $passPhrase;
    public $qrCode;
    public $records;

    public function __construct()
    {
        global $ipfs;
        $this->ipfs = $ipfs;
        $this->records = [];

        //FIXME: Error due to an unknown constant. Had to intialise this class
        $neoPHP = new NeoPHP();
        $this->_user = false;
        if(isset($_SESSION['user']) && !empty($_SESSION['user'])){
            $this->_user = $_SESSION['user'];
        }
    }

    /**
     * User login form action.
     * @param $privateKey : User's generated private KEY
     * @param string $passphrase : Optionnal, use a passphrase to login with the generated private KEY
     * @param string $useRealPrivateKey : Real NEO private KEY. Should not be used directly
     * @return bool
     */
    public function login($privateKey, $passphrase ='', $useRealPrivateKey = false)
    {
        try {
            if($this->_user === false) {
                //TODO: Load NEO wallet with passphrase
                if (empty($passphrase)) {
                    $wallet = new NeoWallet($privateKey);
                    if ($wallet->getAddress()) {
                        $_SESSION['user']['wallet'] = $wallet->getWIF();
                        // @todo denis: update that with KEY/VALUE pair stored within the wallet.
                        $json = file_get_contents('src/test/master_encrypted.json');
                        $encryption = new Encryption();
                        $json = $encryption->decrypt($json);
                        $_SESSION['user']['master'] = json_decode($json);
                        $this->_user = $_SESSION['user'];
                    }
                }
            }
            return $this->_user !== false;
        }
        catch(\Exception $e) {
            return false;
        }

    }

    /**
     * Register a new NEO wallet used for the dAPP
     * @return : TODO
     * @throws \Exception
     */
    public function register($passphrase)
    {
        $newWallet = new NeoWallet();
        //FIXME: Max execution time. For now no encryption
        //$newWallet->encryptWallet($passphrase);

        return $newWallet->getWIF();
    }

    /**
     * Create a user from registration form post.
     *
     * @param $post
     *   The post data from registration form.
     */
    public function createUser($post) {
        $this->fullName = $post['fullName'];
        $this->email = $post['email'];
        $this->dob = $post['dob'];
        $this->type = $post['type'];
        $this->passPhrase = $post['passPhrase'];
    }
    /**
     * Create a user from registration form post.
     *
     * @param $post
     *   The post data from registration form.
     */
    public function createDoctor($post) {
        $this->fullName = $post['doctor_name'];
        $this->email = '';
        $this->dob = '';
        $this->type = User::TYPE_USER_DOCTOR;
        $this->passPhrase = '';
    }

    /**
     * Store the user in ipfs as master document.
     *
     * @return mixed
     */
    public function storeUser() {
        // Store the record locally. >> DEBUG PURPOSE.
        $json = json_encode($this);
        $encryption = new Encryption();
        $json = $encryption->encrypt($json);
        $fileName = 'src/test/master_encrypted.json';
        $myFile = fopen($fileName, 'w+');
        fwrite($myFile, $json);
        fclose($myFile);

        // Store the record in ipfs.
        $json = json_encode($this);
        $hash = $this->ipfs->add($json);

        return $hash;
    }

    /**
     * Test if the user is doctor or patient.
     * @return bool
     */
    public static function isUserDoctor() {
        if (isset($_SESSION['user']['master'])
            && $_SESSION['user']['master']->type === User::TYPE_USER_DOCTOR) {
            return TRUE;
        }
        else {
            return FALSE;
        }
    }


}