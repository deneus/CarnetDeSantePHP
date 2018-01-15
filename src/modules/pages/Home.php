<?php

namespace HealthChain\modules\pages;

use DateTime;
use HealthChain\interfaces\ApplicationView;
use HealthChain\layout\LayoutTrait;
use HealthChain\modules\classes\Entry;

class Home implements ApplicationView
{
    use LayoutTrait;

    private $ipfs;

    private $hashes;

    public function __construct()
    {
        global $ipfs;
        $this->ipfs = $ipfs;
        $this->hashes = $this->generateTestHashes();
    }

    /**
     * {@inheritdoc}
     */
    public function outputHtmlContent()
    {
        $html = '';
        $html .= $this->generateTable();
        $html .= $this->generateFilters();

        return $html;
    }

    /**
     * {@inheritdoc}
     */
    public function outputHtmlHeader() {
        return $this->generateHeader('Welcome to your Health Booklet');
    }


    /**
     * Generate the filters for the table, in javascript.
     *
     * @return string
     *   The html.
     */
    private function generateFilters() {
        $html = '
        <script type="application/javascript">
        $( document ).ready(function() {
            // Documentation; https://www.dynatable.com/
            var dynatable = $("#listOfNotes").dynatable();
        });
        </script>';

        return $html;
    }

    /**
     * Generate the HTML Table.
     *
     * @return string
     *   The html.
     */
    private function generateTable() {
        $html = '<table id="listOfNotes" class="table table-sm table-hover table-striped">';
        $html .= '<thead class="thead-dark">
            <tr>
                <th class="col-1">Date</th>
                <th class="col-2">Who</th>
                <th class="col-5">Comment</th>
                <th class="col-2">Attachments</th>
            </tr>
         </thead>';
        $html .= '<tbody>';

        foreach ($this->hashes as $hash) {
            // The hash is broken.
            if ($hash === NULL) {
                continue;
            }

            $entry = new Entry($hash);

            $html .= '<tr>';
            $html .= '<td>' . $entry->renderDate() . '</td>';
            $html .= '<td>' . $entry->who . '</td>';
            $html .= '<td>' . $entry->comment . '</td>';
            $html .= '<td>' . $entry->renderAttachments() . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody>';
        $html .= '</table>';

        return $html;
    }

    /**
     * Generate test hash.
     *
     * @param int $numberOfHash
     * @return array
     */
    private function generateTestHashes($numberOfHash = 15)
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


            $filename = 'tests/'.$name.mt_rand(1,8).$extension;
            $text = file_get_contents($filename);
            $hash[] = $this->ipfs->add($text);
        }

        return $hash;
    }
}


?>