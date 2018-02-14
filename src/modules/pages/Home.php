<?php

namespace HealthChain\modules\pages;

use HealthChain\interfaces\ApplicationView;
use HealthChain\modules\classes\Record;
use HealthChain\modules\classes\User;
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
        global $directory;

        if (User::isUserDoctor()) {
            $delegationLink = '<a href="' . $directory . '/logout.html" class="btn btn-danger pl-2"><i class="fa fa-user-md mr-1"></i>Terminate your access</a>';;
        }
        else {
            $delegationLink = '<a href="' . $directory . '/accessDelegation.html" class="btn btn-primary pl-2"><i class="fa fa-user-md mr-1"></i>Delegate the access</a>';
        }
        $html = '<div class="row text-right">
                    <div class="mb-4 w-100">
                        <a href="' . $directory . '/newRecord.html" class="btn btn-primary pl-2"><i class="fa fa-plus mr-1"></i>Add new record</a>
                        &nbsp;
                        '.$delegationLink.'
                    </div>
                </div>';

        if (count($this->hashes) === 0 || ($this->hashes[0] === NULL)) {
            $html .= '<div class="row border highlight-background pt-3 pb-3 pl-3">
                You don\'t have any medical record at the moment. <br /><br />
                Either create a record yourself by using New Record, either delegate access to your doctor.
            </div>';
            return $html;
        }

        $html .= '<table id="listOfNotes" class="table table-sm table-hover table-striped">';
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

            $record = new Record();
            $record->getRecordFromHash($hash);

            $html .= '<tr>';
            $html .= '<td>' . $record->renderDate() . '</td>';
            $html .= '<td>' . $record->renderWho() . '</td>';
            $html .= '<td>' . $record->comment . '</td>';
            $html .= '<td>' . $record->renderAttachments() . '</td>';
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

