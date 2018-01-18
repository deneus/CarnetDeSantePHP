<?php

namespace HealthChain\test;

use stdClass;

class Tests {

    protected $ipfs;

    public function __construct()
    {
        global $ipfs;
        $this->ipfs = $ipfs;
    }

    /**
     * Generate test hash.
     *
     * @param int $numberOfHash
     * @return array
     */
    public function generateTestHashes($numberOfHash = 15)
    {
        $attachments = $this->generateAttachments() ;

        $hash = [];
        for ($i = 1; $i < 5; $i++) {

            $filename = 'src/test/hash'.$i.'.json';
            $text = file_get_contents($filename);
            /* @var $json StdClass */
            $json = json_decode($text);

            // reset attachments.
            $json->attachments = [];

            for ($j=0;$j<mt_rand(0,3);$j++) {
                $json->attachments[$j] = $attachments[mt_rand(1,8)] ;
            }

            $fullEntry = json_encode($json);

            $hash[] = $this->ipfs->add($fullEntry);
        }

        return $hash;
    }

    private function generateAttachments() {
        $hash = [];

        for($i=1; $i<9; $i++) {
            $filename = 'src/test/image'.$i.'.jpg';
            $text = file_get_contents($filename);
            $hash[$i] = [
                'hash' => $this->ipfs->add($text),
                'mimetype' => 'image/jpg',
                'type' => 'prescription',
            ];
        }

        return $hash;
    }
}