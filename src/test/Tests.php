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

        $directory = 'src/test';
        $scanned_directory = array_diff(scandir($directory), array('..', '.'));

        $hash = [];
        foreach ($scanned_directory as $file) {

            if(explode('.', $file)[1] === 'json') {
                $filename = $directory .'/'.$file;
                $text = file_get_contents($filename);
                /* @var $json StdClass */
                $json = json_decode($text);

                $fullEntry = json_encode($json);

                $hash[] = $this->ipfs->add($fullEntry);
            }

        }

        return $hash;
    }

    /**
     * Generate some random attachments.
     * 
     * @return array
     */
    private function generateAttachments() {
        $hash = [];

        for($i=1; $i<9; $i++) {
            $filename = 'src/test/images/image'.$i.'.jpg';
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