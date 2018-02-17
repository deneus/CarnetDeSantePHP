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
        $html = '<div class="mx-1 mx-sm-2">
                    '.date('d/m/o', $this->date).'
                    <span class="d-none d-lg-inline"><br />
                    '.date('G:i', $this->date).'</span>
                </div>';

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

        $html = '<ul class="attachments pl-0">';
        foreach ($this->attachments as $key => $attachment) {
            if (is_array($attachment)) {
                $attachment = (object)$attachment;
            }
            $url = 'attachment.php?hash='.$attachment->hash.'&type='.$attachment->mimetype;
            $html .='<li class="mx-1 mx-sm-2">
                        <a target="_blank" href="'.$url.'">
                            <i class="fa fa-file-alt mr-2"></i>
                            <span class="d-none d-lg-inline">'.$attachment->type.'</span>
                            <span class="d-inline d-lg-none">'.substr($attachment->type, 0, 3).'...</span>
                        </a>
                     </li>';
        }
        $html .= '</ul>';

        return $html;

    }

    /**
     * Render comments.
     *
     * @return string
     *   The html.
     */
    public function renderComment() {
        $html = '<div class="d-none d-lg-block mx-1 mx-sm-2"><span>'.$this->comment.'</span></div>
                <div class="d-block d-lg-none mx-1 mx-sm-2"><span>'.substr($this->comment, 0, 100).'...</span></div>';

        return $html;
    }

    /**
     * Render who.
     *
     * @return string
     *   Who formatted.
     */
    public function renderWho() {
        if ($this->who_speciality === '') {
            $who = $this->who_name;
        }else {
            $who = $this->who_name . ' <br /><i>' . $this->who_speciality .'</i>';
        }

        $html = '<div class="d-block mx-1 mx-sm-2"><span>'.$who.'</span></div>';

        return $html;
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