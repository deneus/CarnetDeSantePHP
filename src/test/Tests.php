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
        $nbAttachments = 3;
        $attachments = $this->generateAttachments(3) ;

        $hash = [];
        for ($i = 0; $i < $numberOfHash; $i++) {

            $filename = 'src/test/hash'.mt_rand(1,8).'.txt';
            $text = file_get_contents($filename);
            $customAttachments = array_slice($attachments, 0, mt_rand(0, $nbAttachments));
            $text .= implode('###', $customAttachments);
            $text .= '###';
            $hash[] = $this->ipfs->add($text);
        }

        return $hash;
    }

    private function generateAttachments($nbAttachments) {
        $hash = [];

        for ($i=0; $i<$nbAttachments; $i++){
            $filename = 'src/test/image'.mt_rand(1,8).'.jpg';
            $text = file_get_contents($filename);
            $hash[] = $this->ipfs->add($text);
        }

        return $hash;
    }
}