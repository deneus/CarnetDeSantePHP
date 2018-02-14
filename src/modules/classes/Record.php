<?php

namespace HealthChain\modules\classes;

use DateTime;
use HealthChain\layout\MessagesTraits;

class Record
{
    use MessagesTraits;

    private $ipfs;
    private $hash;
    public $who;
    public $date;
    private $size;
    public $comment;
    public $attachments;

    public function __construct() {
        global $ipfs;

        $this->ipfs = $ipfs;
        $this->attachments = [];
        $this->who = new RecordWho();
    }

    /**
     * Populate arecord from an hash.
     *
     * @param $hash
     */
    public function getRecordFromHash($hash)
    {
        $this->hash = $hash;

        $text = $this->ipfs->cat($this->hash);
        $text = str_replace('a831rwxi1a3gzaorw1w2z49dlsor', '', $text);

        $json = json_decode($text);
        $this->size = $this->ipfs->size($hash);

        $this->comment = $json->comment;
        $this->who = $json->who;

        $date = new DateTime();
        $this->date = $date->setTimestamp($json->date);

        $this->attachments = $json->attachments;
    }

    /**
     * Render the date.
     *
     * @return string
     *   The html.
     */
    public function renderDate() {
        $html = $this->date->format('d/m/o');
        $html .= '<br /> at ';
        $html .= $this->date->format('G:i');
        return $html;
    }

    /**
     * Render attachments.
     *
     * @return string
     *   The html.
     */
    public function renderAttachments() {
        if (count($this->attachments) === 0) {
            return '';
        }

        $html = '<ul>';
        foreach ($this->attachments as $key => $attachment) {
            $html .='<li><a target="_blank" href="attachment.php?hash='.$attachment->hash.'&type='.$attachment->mimetype.'">'.$attachment->type.'</a></li>';
        }
        $html .= '</ul>';

        return $html;

    }

    /**
     * Render who.
     *
     * @return string
     *   Who formatted.
     */
    public function renderWho() {
        $output = $this->who->name . ' <br /><i>' . $this->who->speciality .'</i>';
        if ($this->who->speciality === '') {
            $output = $this->who->name;
        }
        return $output;
    }

    /**
     * Set record date to now().
     */
    public function setDateToNow() {
        $date = new DateTime();
        $this->date = $date->getTimestamp();
    }

    /**
     * Store the record in ipfs.
     *
     * @return mixed
     */
    public function storeRecord() {
        // Store the record in ipfs.
        $json = json_encode($this);
        $hash = $this->ipfs->add($json);

        // Add the record into master.
        $_SESSION['user']['master']->records[] = $hash;
        // Save master.
        $json = json_encode($_SESSION['user']['master']);
        $this->ipfs->add($json);

        // Store the New Record locally. >> DEBUG PURPOSE.
        $json = json_encode($this);
        $fileName = 'src/test/' . $hash . '.json';
        $myFile = fopen($fileName, 'w+');
        fwrite($myFile, $json);
        fclose($myFile);

        // Override the master locally >> DEBUG PURPOSE.
        $json = json_encode($_SESSION['user']['master']);
        $fileName = 'src/test/master.json';
        $myFile = fopen($fileName, 'w+');
        fwrite($myFile, $json);
        fclose($myFile);

        return $hash;
    }
}