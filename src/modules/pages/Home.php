<?php

namespace HealthChain\modules\pages;

use HealthChain\interfaces\ApplicationView;
use HealthChain\modules\classes\Record;
use HealthChain\modules\classes\User;
use HealthChain\test\Tests;

class Home implements ApplicationView
{
    private $records;

    public function __construct()
    {
        $this->records = $this->getRecords();
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
        /**
         * See scripts.js.
         * var dynatable = $(".list-of-records").dynatable();
         */

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

        if (count($this->records) === 0 || ($this->records[0] === NULL)) {
            $html .= '<div class="row border highlight-background pt-3 pb-3 pl-3">
                You don\'t have any medical record at the moment. <br /><br />
                Either create a record yourself by using New Record, either delegate access to your doctor.
            </div>';
            return $html;
        }

        $html .= '<table id="listOfRecords" class="list-of-records">';
        $html .= '<thead class="">
            <tr>
                <th class="col-1">Date</th>
                <th class="col-2">Who</th>
                <th class="col-5">Comment</th>
                <th class="col-2">Attachments</th>
            </tr>
         </thead>';
        $html .= '<tbody>';

        /** @var $record Record*/
        foreach ($this->records as $record) {
            // The hash is broken.
            if ($record === NULL) {
                continue;
            }

            $html .= '<tr>';
            $html .= '<td>' . $record->renderDate() . '</td>';
            $html .= '<td>' . $record->renderWho() . '</td>';
            $html .= '<td>' . $record->renderComment() . '</td>';
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
        $records = [];
        foreach($_SESSION['user']['master']->records as $stdClass) {
            $record = new Record();
            $record->setRecord($stdClass);
            $records[] = $record;
        }
        return array_reverse($records);
    }
}

