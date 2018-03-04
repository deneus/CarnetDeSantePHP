<?php

namespace HealthChain\modules\pages;

use HealthChain\interfaces\ApplicationView;
use HealthChain\layout\MessagesTraits;
use HealthChain\modules\classes\Record;
use HealthChain\modules\traits\FormTrait;
use HealthChain\modules\traits\PostTrait;
use stdClass;

class NewRecord implements ApplicationView
{
    use MessagesTraits;
    use PostTrait;
    use FormTrait;

    public $ipfs;
    public $record;
    private $_action;

    const ACTION_DISPLAY_FORM = 'display';
    const ACTION_SUBMIT_FORM = 'submit';

    public function __construct()
    {
        global $ipfs;

        $this->ipfs = $ipfs;
        $this->record = new Record();

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
        if (count($_FILES)> 0) {
            $this->processFile($_FILES['file']);
        }

        if ($this->_action === self::ACTION_SUBMIT_FORM) {
            if ($post['action'] === 'fields-storage') {
                $html .= $this->processForm($post);
            }
        }

        return $html;
    }

    /**
     * Render the form to add an record.
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
<form action="newRecord.html" id="new_record" method="post">
    
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
<br /><i>Accept only *.jpg, *.jpeg, *.pdf./</i>

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

         $this->record->setDateToNow();
         $this->record->who_name = $post['doctor_name'];
         $this->record->who_speciality = $post['doctor_speciality'];
         $this->record->comment = $post['comment'];

         if (!empty($_SESSION['uploaded_file'])) {
            foreach($_SESSION['uploaded_file'] as $file) {
                $this->record->attachments[] = $file;
            }
            $_SESSION['uploaded_file'] = [];
         }

         $hash = $this->record->storeRecord();

         if ($hash !== NULL) {
            $_SESSION['uploaded_file'] = [];
            $html = $this->generateSuccessMessage('Your record has been saved!');
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

            if($_SESSION['uploaded_file'] === '') {
                $_SESSION['uploaded_file'] = new StdClass();
            }

            $std = new StdClass();
            $std->hash = $this->ipfs->add($textFromImage);
            $std->mimetype = $file['type'];
            $std->type = 'attachment';
            $_SESSION['uploaded_file'][] = $std;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function outputTitle() {
        return 'New Record';
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

    /**
     * Return the CSS class for the banner > display a background image.
     *
     * @return mixed
     */
    public function cssClassForBanner()
    {
        return 'bg-banner-image-1';
    }
}