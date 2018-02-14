<?php

namespace HealthChain\modules\pages;

use HealthChain\interfaces\ApplicationView;
use HealthChain\layout\MessagesTraits;
use HealthChain\modules\classes\Entry;
use HealthChain\modules\traits\FormTrait;
use HealthChain\modules\traits\PostTrait;

class NewRecord implements ApplicationView
{
    use MessagesTraits;
    use PostTrait;
    use FormTrait;

    public $ipfs;
    public $entry;
    private $_action;

    const ACTION_DISPLAY_FORM = 'display';
    const ACTION_SUBMIT_FORM = 'submit';

    public function __construct()
    {
        global $ipfs;

        $this->ipfs = $ipfs;
        $this->entry = new Entry();

        $this->_action = self::ACTION_DISPLAY_FORM;
    }

    /**
     * Generate the content html to output.
     *
     * @return String
     *   The HTML to output.
     *
     * @throws \Exception
     */
    public function outputHtmlContent()
    {
        $html = $this->processPost();
        $html .= $this->renderAddForm();
        return $html;
    }


    /**
     * Process all possible post in the page.
     *
     * @return string
     *   The html to output.
     *
     * @throws \Exception
     */
    public function processPost() {
        $post = $this->sanitize($_POST);
        if (count($post) > 0) {
            $this->_action = self::ACTION_SUBMIT_FORM;
        }

        $html = '';
        if ($this->_action === self::ACTION_SUBMIT_FORM) {
            if ($post['action'] === 'fields-storage') {
                $html .= $this->processForm($post);
            }
        }

        if (count($_FILES)> 0) {
            $this->processFile($_FILES['file']);
        }
        return $html;
    }

    /**
     * Render the form to add an entry.
     *
     * @return string
     *   The Html.
     */
    public function renderAddForm() {
        $fieldDoctorName = $this->renderFieldDoctorName();
        $fieldDoctorSpeciality = $this->renderFieldDoctorSpeciality();
        $fieldComment = $this->renderFieldComment();
        $starIsMandatory = $this->renderStarIsMandatory();
        $submitButton = $this->renderSubmitButton('Submit');

        $html = <<<EOS
<form action="newRecord.html" id="new_entry" method="post">
    
    $fieldDoctorName
    
    $fieldDoctorSpeciality

    $fieldComment
    
    $starIsMandatory

    <input type="hidden" name="action" value="fields-storage" /> 
    
    $submitButton

</form>

<form  action="newRecord.html" class="dropzone mt-4" id="my-awesome-dropzone" >
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
         if (!$this->isPostFull($post)) {
             $html = $this->generateFailMessage('All fields are mandatory.');
             return $html;
         }

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
            $_SESSION['uploaded_file'] = NULL;
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
                'type' => 'attachment',
            ];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function outputTitle() {
        return 'New Entry';
    }

    /**
     * Test if the POST is complete.
     *
     * @param $post
     *   The sanitized POST.
     *
     * @return bool
     *   The verification status.
     */
    public function isPostFull($post) {
        return !($post['doctor_name'] === ''
            || $post['doctor_speciality'] === 'default'
            || $post['comment'] === '');
    }

    /**
     * {@inheritdoc}
     */
    public function cssClassForContent() {
        return '';
    }
}