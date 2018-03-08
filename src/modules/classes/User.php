<?php

namespace HealthChain\modules\classes;


use HealthChain\modules\classes\Neo\Contract;
use HealthChain\modules\classes\Neo\NeoAPI;
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

    const NEO_METHOD_REGISTER = 'register';
    const NEO_METHOD_LOGIN = 'login';
    const NEO_METHOD_REGMASTER = 'registerMaster';

    public function __construct()
    {
        global $ipfs;
        $this->ipfs = $ipfs;
        $this->records = [];
        $this->_user = false;
        if(isset($_SESSION['user']) && !empty($_SESSION['user'])){
            $this->_user = $_SESSION['user'];
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
        try {
            //L4kWiLZVHwoHqTR8bvUWd3ecCubVDBWT2CW777LeK6RteCiJWgiN
            if($this->_user === false) {
                //TODO: Load NEO wallet with passphrase
                if (empty($passphrase)) {
                    $response = NeoAPI::call(self::NEO_METHOD_LOGIN, NeoAPI::METHOD_POST, ['key' =>$privateKey]);
                    $response = json_decode($response);
                    if (!empty($response->address)) {
                        $_SESSION['user']['wallet'] = $response->wif;
                        $_SESSION['user']['address'] = $response->address;
                        // @todo denis: update that with KEY/VALUE pair stored within the wallet.
                        //Neon-Js does not have an updated database of RPC's node. Let's get it with PHP

                        $neo = new \NeoPHP\NeoRPC($GLOBALS['mainnet']);
                        $url = $neo->getFastestNode();

                        $params = array('hash' => Contract::CONTRACT_HASH);

                        $masterResponse = NeoAPI::call(self::NEO_METHOD_MASTER, NeoAPI::METHOD_POST, $params);


                        $json = file_get_contents('src/test/master.json');
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
     */
    public function register($passphrase)
    {
        $response = NeoAPI::call(self::NEO_METHOD_REGISTER);
        $response = json_decode($response);

        var_dump($response);
        var_dump($response->address);
        //Now that the WIF have been generated, we need to generate the master for the user
        if(isset($response->wif)){
            $params = array('hash' => Neo\Contract::CONTRACT_HASH,
                'NEOaddress' => $response->address,
                'ipfsMaster' => 'DENIS TO INTEGRATE');

            $result = Neo\NeoAPI::call(\HealthChain\modules\classes\User::NEO_METHOD_REGMASTER, Neo\NeoAPI::METHOD_POST,
                $params);
            //TODO: Proper handling of exceptions
        }
        return $response->wif;
    }

    /**
     * Create a user from registration form post.
     *
     * @param $post
     *   The post data from registration form.
     */
    public function createUser($post)
    {
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
        // Store the entry locally. >> DEBUG PURPOSE.
        $json = json_encode($this);
        $fileName = 'src/test/master.json';
        $myFile = fopen($fileName, 'w+');
        fwrite($myFile, $json);
        fclose($myFile);

        // Store the entry in ipfs.
        $json = json_encode($this);
        $hash = $this->ipfs->add($json);

        return $hash;
    }

    public function getListDocs()
    {

    }

    public static function isUserDoctor() {
        if ($_SESSION['user']['master']->type === User::TYPE_USER_DOCTOR) {
            return TRUE;
        }
        else {
            return FALSE;
        }
    }


}