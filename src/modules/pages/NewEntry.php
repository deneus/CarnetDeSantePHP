<?php

namespace HealthChain\modules\pages;

use HealthChain\interfaces\ApplicationView;
use HealthChain\layout\LayoutTrait;
use HealthChain\layout\MessagesTraits;
use HealthChain\modules\classes\Entry;
use HealthChain\modules\traits\PostTrait;

class NewEntry implements ApplicationView
{
    use LayoutTrait;
    use MessagesTraits;
    use PostTrait;

    public $ipfs;
    public $entry;

    public function __construct()
    {
        global $ipfs;

        $this->ipfs = $ipfs;
        $this->entry = new Entry();
    }

    /**
     * Generate the header html to output.
     *
     * @return mixed
     *   The HTML to output.
     */
    public function outputHtmlHeader()
    {
        return $this->generateHeader('Health Booklet - Add new entry.');
    }

    /**
     * Generate the content html to output.
     *
     * @return String
     *   The HTML to output.
     */
    public function outputHtmlContent()
    {
        $html = $this->processPost();
        $html .= $this->renderAddForm();
        return $html;
    }

    public function processPost() {
        $post = $this->sanitize($_POST);

        $html = '';
        if (isset($_GET['action'])) {
            if ($_GET['action'] === 'fields-storage') {
                $html .= $this->processForm($post);
            }

            if ($_GET['action'] === 'file-upload') {
                $this->processFile($_FILES['file']);
            }
        }
        else {
            $_SESSION['uploaded_file'] = NULL;
        }
        return $html;
    }

    public function renderAddForm() {
        $html = <<<EOS
<form action="?q=newEntry&action=fields-storage"  id="new_entry" method="post">
    <div class="form-group required ">
        <label for="doctor_name">Doctor Name</label>
        <input type="text" class="form-control" id="doctor_name" name="doctor_name" placeholder="Dr Schmidt">
    </div>

    <div class="form-group required ">
        <label for="doctor_speciality">Speciality</label>
        <select class="form-control custom-select" id="doctor_speciality" name="doctor_speciality">
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
        <textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>

</form>

<form action="?q=newEntry&action=file-upload" class="dropzone mt-4" id="my-awesome-dropzone">
      <div class="fallback">
        <input name="file" type="file" multiple />
      </div>
</form>

EOS;

        return $html;
    }


    /**
     * Process the post.
     *
     * @param $post
     *   The sanitized POST.
     *
     * @return string
     *   The message in html.
     */
    public function processForm($post) {

        $this->entry->setDateToNow();
        $this->entry->who->name = $post['doctor_name'];
        $this->entry->who->speciality = $post['doctor_speciality'];
        $this->entry->comment = $post['comment'];

        if (!empty($_SESSION['uploaded_file'])) {
            foreach($_SESSION['uploaded_file'] as $file) {
                $this->entry->attachments[] = $file;
            }
            $_SESSION['uploaded_file'] = '';

        }
        $hash = $this->entry->storeEntry();

        if ($hash !== NULL) {
            $html = $this->generateSuccessMessage('Your entry has been saved!');
        }
        else {
            $html = $this->generateFailMessage();
        }
        return $html;
    }

    /**
     * Ajax call to process uploaded files. Add files into session.
     * @param $file
     */
    public function processFile($file) {

        if ($file !== NULL) {
            $textFromImage = file_get_contents($file['tmp_name']);

            $_SESSION['uploaded_file'][] = [
                'hash' => $this->ipfs->add($textFromImage),
                'mimetype' => $file['type'],
                'type' => 'prescription',
            ];
        }
    }
}