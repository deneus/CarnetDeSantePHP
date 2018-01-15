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
    public $entry_separator;

    public function __construct($hash)
    {
        global $ipfs;
        global $entry_separator;

        $this->entry_separator = $entry_separator;

        $this->ipfs = $ipfs;
        $this->hash = $hash;
        //$this->size = $this->ipfs->size($hash);
        $this->size = 1;

        $content = $this->parseContent($this->hash);

        $this->size = $this->ipfs->size($hash);

        $this->comment = $content['comment'];
        $this->who = $content['who'];

        $this->date = new DateTime();
        $this->date->setTimestamp($content['date']);

        $this->attachments = $content['attachments'];
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
            $html .='<li><a target="_blank" href="attachment.php?hash=' . trim($attachment) . '">Attachment ' . ($key+1) . '</a></li>';
        }
        $html .= '</ul>';

        return $html;

    }

    /**
     * Parse ipfs stored content.
     *
     * @param string $hash
     *
     * @return array
     *   The stored content.
     */
    public function parseContent($hash) {
        // Date ### Doctor ### Speciality ### Comment ### Attachments_1 ### Attachments_2 ###
        $array = explode($this->entry_separator, $this->ipfs->cat($hash));
        array_pop($array);
        $output = [
            'date' => trim($array[0]),
            'who' => $this->formatWho($array),
            'comment' => trim($array[3]),
            'attachments' => array_slice($array, 4),
        ];
        return $output;
    }

    /**
     * Format who.
     *
     * @param $array
     *
     * @return string
     *   Who formatted.
     */
    private function formatWho($array) {
        $output = trim($array[1]) . ' <br /><i>' . trim($array[2]) .'</i>';
        if ($array[2] === '') {
            $output = trim($array[1]);
        }
        return $output;
    }
}