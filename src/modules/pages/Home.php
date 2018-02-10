<?php

namespace HealthChain\modules\pages;

use HealthChain\interfaces\ApplicationView;
use HealthChain\modules\classes\Entry;
use HealthChain\test\Tests;

class Home implements ApplicationView
{
    private $hashes;

    public function __construct()
    {
        $this->hashes = $this->getRecords();
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
     * Generate the filters for the table, in javascript.
     *
     * @return string
     *   The html.
     */
    private function generateFilters()
    {
        $html = '
        <script type="application/javascript">
        $( document ).ready(function() {
            // Documentation; https://www.dynatable.com/
            var dynatable = $("#listOfNotes").dynatable();
        });
        </script>';

        return '';
    }

    /**
     * Generate the HTML Table.
     *
     * @return string
     *   The html.
     */
    private function generateTable() {
        if (count($this->hashes) === 0 || ($this->hashes[0] === NULL)) {
            $html = '<div class="row border highlight-background pt-3 pb-3 pl-3">
                You don\'t have any medical entry at the moment. <br /><br />
                Either create an entry yourself by using New Entry, either delegate access to your doctor.
            </div>';
            return $html;
        }

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

            $entry = new Entry();
            $entry->getEntryFromHash($hash);

            $html .= '<tr>';
            $html .= '<td>' . $entry->renderDate() . '</td>';
            $html .= '<td>' . $entry->renderWho() . '</td>';
            $html .= '<td>' . $entry->comment . '</td>';
            $html .= '<td>' . $entry->renderAttachments() . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody>';
        $html .= '</table>';

        return $html;
    }

    public function outputTitle() {
        return 'Home';
    }

    public function cssClassForContent() {
        return '';
    }

    /**
     * Generate test hash.
     *
     * @return array
     *   An array of hash.
     */
    public function getRecords()
    {
        return array_reverse($_SESSION['user']['master']->records);
    }
}

