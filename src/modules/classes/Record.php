<?php

namespace HealthChain\modules\classes;

use DateTime;
use HealthChain\layout\MessagesTraits;
use stdClass;

class Record
{
    use MessagesTraits;

    private $ipfs;
    private $hash;
    public $who_name;
    public $who_speciality;
    public $date;
    private $size;
    public $comment;
    public $attachments;

    public function __construct() {
        global $ipfs;

        $this->ipfs = $ipfs;
        $this->attachments = [];
    }

    /**
     * Populate a record from an hash.
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
        $this->who_name = $json->who_name;
        $this->who_speciality = $json->who_speciality;

        $date = new DateTime();
        $this->date = $date->setTimestamp($json->date);

        $this->attachments = $json->attachments;
    }

    /**
     * Populate a record from a StdClass.
     *
     * @param stdClass $record
     */
    public function setRecord(StdClass $record) {
        $this->who_name = $record->who_name;
        $this->who_speciality = $record->who_speciality;
        $this->date = $record->date;
        $this->comment = $record->comment;
        $this->attachments = $record->attachments;
    }

    /**
     * Render the date.
     *
     * @return string
     *   The html.
     */
    public function renderDate() {
        $html = date('d/m/o', $this->date);
        $html .= '<br /> at ';
        $html .= date('G:i', $this->date);
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
            $html .='<li><a target="_blank" href="attachment.php?hash='.$attachment['hash'].'&type='.$attachment['mimetype'].'">'.$attachment['type'].'</a></li>';
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
        $output = $this->who_name . ' <br /><i>' . $this->who_speciality .'</i>';
        if ($this->who_speciality === '') {
            $output = $this->who_name;
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
        $_SESSION['user']['master']->records[] = $this->prepareRecord();
        $json = json_encode($_SESSION['user']['master']);
        $hash = $this->ipfs->add($json);

        // Override the master locally >> DEBUG PURPOSE.
        $json = json_encode($_SESSION['user']['master']);
        $fileName = 'src/test/master.json';
        $myFile = fopen($fileName, 'w+');
        fwrite($myFile, $json);
        fclose($myFile);

        return $hash;
    }

    public function prepareRecord() {
        $stdClass = new StdClass();
        $stdClass->who_name = $this->who_name;;
        $stdClass->who_speciality = $this->who_speciality;
        $stdClass->date = $this->date;
        $stdClass->comment = $this->comment;
        $stdClass->attachments = $this->attachments;

        return $stdClass;
    }


    public function storeRecordAsSplitFiles() {
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