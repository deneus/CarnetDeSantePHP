<?php

namespace HealthChain\modules\classes;

class Encryption
{
    const ENCRYPT_METHOD = 'AES-256-CBC';
    const IV = 'HealthBookletV3.0';
    private $key;
    private $iv;

    public function __construct() {
        $secretKey = $_SESSION['user']['wallet'];
        $this->key = hash('sha256', $secretKey);
        $this->iv = substr(hash('sha256', self::IV), 0, 16);
    }

    public function encrypt($string) {
        $output = openssl_encrypt($string, self::ENCRYPT_METHOD, $this->key, 0, $this->iv);
        $output = base64_encode($output);
        return $output;
    }

    public function decrypt($string){
        $output = openssl_decrypt(base64_decode($string), self::ENCRYPT_METHOD, $this->key, 0, $this->iv);
        return $output;
    }

}