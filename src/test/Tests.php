<?php

namespace HealthChain\test;

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
        $hash = [];
        for ($i = 0; $i < $numberOfHash; $i++) {

            // Version 1.
            //$loremIpsum = simplexml_load_file('http://www.lipsum.com/feed/xml?amount=1&what=paras&start=0')->lipsum;
            // Version 2.
            // Speed up the process of loading elements by avoiding calling lipsum feed.

            $typeNumber = mt_rand(1,1);
            switch ($typeNumber) {
                case 1:
                    $name = 'hash';
                    $extension = '.txt';
                    break;
                case 2:
                    $name = 'image';
                    $extension = '.jpg';
                    break;
            }


            $filename = 'src/test/'.$name.mt_rand(1,8).$extension;
            $text = file_get_contents($filename);
            $hash[] = $this->ipfs->add($text);
        }

        return $hash;
    }
}