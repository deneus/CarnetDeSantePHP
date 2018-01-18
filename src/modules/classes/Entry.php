<?php

namespace HealthChain\modules\classes;

use DateTime;
use HealthChain\layout\MessagesTraits;

class Entry
{
    use MessagesTraits;

    public $ipfs;
    public $hash;
    public $who;
    public $date;
    public $size;
    public $comment;
    public $attachments;

    public function __construct($hash)
    {
        global $ipfs;

        $this->ipfs = $ipfs;
        $this->hash = $hash;

        $text = $this->ipfs->cat($this->hash);
        $text = str_replace('a831rwxi1a3gzaorw1w2z49dlsor', '', $text);

        $json = json_decode($text);
        $this->size = $this->ipfs->size($hash);

        $this->comment = $json->comment;
        $this->who = $json->who;

        $this->date = new DateTime();
        $this->date->setTimestamp($json->date);

        $this->attachments = $json->attachments;
    }

    /**
     * Render the date.
     *
     * @return string
     *   The html.
     */
    public function renderDate() {
        return $this->date->format('d/m/o');
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
     * @param $array
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
}