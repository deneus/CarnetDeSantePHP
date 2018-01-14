<?php

namespace HealthChain\modules\classes;

use DateTime;
use HealthChain\layout\MessagesTraits;

class Entry
{
    use MessagesTraits;

    public $who;
    public $date;
    public $comment;
    public $attachments;
    public $ipfs;

    public function __construct()
    {
        global $ipfs;

        $this->who = '';
        $this->date = new DateTime();
        $this->comment = '';
        $this->attachments = [];
        $this->ipfs = $ipfs;
    }

    public function renderDate() {
        return $this->date->format('d/m/o');
    }

    public function renderAttachments() {
        return '<ul>
<li>attachement 1</li>
<li>attachement 2</li>
</ul>';
    }

    public function renderAddForm() {
        $html = <<<EOS
<form id="new_entry" action="?q=newEntry&action=fields-storage">
    <div class="form-group required ">
        <label for="doctor_name">Doctor Name</label>
        <input type="text" class="form-control" id="doctor_name" placeholder="Dr Schmidt">
    </div>

    <div class="form-group required ">
        <label for="doctor_speciality">Speciality</label>
        <select class="form-control custom-select" id="doctor_speciality">
            <option value="default">-- Please Choose --</option>
            <optgroup label="Type of doctor">
                <option>General Medicine</option>
                <option>Other</option>
            </optgroup>
            <optgroup label="Relatives">
                <option>Parent</option>
                <option>Me</option>
            </optgroup>
        </select>
    </div>
    
    <div class="form-group required ">
        <label for="comment">Comment</label>
        <textarea class="form-control" id="comment" rows="3"></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>

</form>

<form action="?q=newEntry&action=file-upload" class="dropzone" id="my-awesome-dropzone">
      <div class="fallback" style="border:2px solid red">
        <input name="file" type="file" multiple />
      </div>
</form>

EOS;

        return $html;
    }

    public function processPost() {
        $html = '';
        if (isset($_GET['action'])) {
            if ($_GET['action'] === 'fields-storage') {
                $html .= $this->processFields();
            }

            if ($_GET['action'] === 'file-upload') {
                $this->processFile();
            }
        }
        return $html;
    }

    /**
     * @return string
     *   The
     */
    public function processFields() {
        // @todo: pareil qu'avec processFile, comment je lie l'user avec son hash?

        if (TRUE) {
            $this->generateSuccessMessage('Your entry has been saved.');
        }
        else {
            $this->generateFailMessage();
        }
        return '';
    }

    /**
     * Ajax call to process uploaded files.
     */
    public function processFile() {
        if (!empty($_FILES)) {
            $filename = $_FILES['file']['tmp_name'];
            $text = file_get_contents($filename);
            $hash = $this->ipfs->add($text);
            // todo: comment lier le hash de l'entré avec les hash des documents ?
        }
    }
}