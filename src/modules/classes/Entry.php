<?php

namespace HealthChain\modules\classes;

use DateTime;
use HealthChain\layout\MessagesTraits;

class Entry
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
        $this->who = new EntryWho();
    }

    /**
     * Populate an entry from an hash.
     *
     * @param $hash
     */
    public function getEntryFromHash($hash)
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
     * Set entry date to now().
     */
    public function setDateToNow() {
        $date = new DateTime();
        $this->date = $date->getTimestamp();
    }

    /**
     * Store the entry in ipfs.
     *
     * @return mixed
     */
    public function storeEntry() {
        // Store the entry locally. >> DEBUG PURPOSE.
        $json = json_encode($this);
        $fileName = 'src/test/' . $this->date . '.json';
        $myFile = fopen($fileName, 'w+');
        fwrite($myFile, $json);
        fclose($myFile);

        // Store the entry in ipfs.
        $json = json_encode($this);
        $hash = $this->ipfs->add($json);

        return $hash;
    }
}