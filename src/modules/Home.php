<?php

namespace HealthChain\modules;

use HealthChain\interfaces\ApplicationView;
use HealthChain\layout\LayoutTrait;

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
                <th scope="col">Who</th>
                <th scope="col">Date</th>
                <th scope="col">Comment</th>
            </tr>
         </thead>';
        $html .= '<tbody>';

        foreach ($this->hashes as $hash) {
            $who = 'TBD';
            $date = new \DateTime();
            // Remove 'a831rwxi1a3gzaorw1w2z49dlsor' at the end of the cat.
            // I still don't know why this key is displayed.
            $comment = $this->ipfs->cat($hash);
            $lastSpace = strrpos($comment, " ");
            $comment = substr($comment, 0, $lastSpace);

            $html .= '<tr>';
            $html .= '<td>' . $who . '</td>';
            $html .= '<td>' . $date->format('d/m/o') . '</td>';
            $html .= '<td>' . $comment . '</td>';
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
            $filename = 'tests/hash'.mt_rand(1,8).'.txt';
            $text = file_get_contents($filename);
            $hash[] = $this->ipfs->add($text);
        }

        return $hash;
    }
}


?>