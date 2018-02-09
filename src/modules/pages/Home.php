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
        $testsHashes = new Tests();
        $this->hashes = $testsHashes->generateTestHashes();
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
}

