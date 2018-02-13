<?php

namespace HealthChain\modules\pages;

use HealthChain\interfaces\ApplicationView;
use HealthChain\layout\MessagesTraits;
use HealthChain\modules\classes\User;
use HealthChain\modules\traits\FormTrait;
use HealthChain\modules\traits\PostTrait;
use HealthChain\modules\traits\QrCodeTrait;

class AccessDelegation implements ApplicationView
{

    use MessagesTraits;
    use FormTrait;
    use PostTrait;
    use QrCodeTrait;

    private $_action;
    protected $qrCode;

    const ACTION_DISPLAY_FORM = 'display';
    const ACTION_DISPLAY_QRCODE = 'qrcode';

    /**
     * Generate the content html to output.
     *
     * @return String
     *   The HTML to output.
     * @throws \Exception
     */
    public function outputHtmlContent()
    {
        if(User::isUserDoctor()) {
            return $this->generateFailMessage('You are not authorised to access this page.');
        }

        $post = $this->sanitize($_POST);
        $this->defineAction($post);

        $errorMessage = '';
        if(count($post) > 0
            && !$this->isPostFull($post)){
            $errorMessage = $this->generateFailMessage('All fields are mandatory.');
            $this->_action = self::ACTION_DISPLAY_FORM;
        }

        switch ($this->_action) {
            case self::ACTION_DISPLAY_FORM;
                $html = $this->renderAccessDelegationForm();
                break;
            case self::ACTION_DISPLAY_QRCODE;
                $html = $this->renderAccessDelegationComplete($post);
                break;
            default;
                break;
        }

        return $errorMessage. $html;
    }

    /**
     * Define the action to process.
     *
     * @param $post
     */
    public function defineAction($post) {
        if (count($post) > 0) {
            $this->_action = self::ACTION_DISPLAY_QRCODE;
        } else {
            $this->_action = self::ACTION_DISPLAY_FORM;
        }
    }

    /**
     * Render for completion message and the qrcode of the doctor.
     *
     * @return string
     *   The html.
     */
    public function renderAccessDelegationComplete($post) {
        // Generate user key.
        $user = new User();
        $user->createDoctor($post);
        $this->userKey = $user->register($user->passPhrase);
        // @todo: needs to process $post['delegation_time'] within the smart contract generation
        $this->qrCode = $this->generateQrCode($this->userKey);

        $qrCode = $this->displayQrCode($this->qrCode);

        $html = <<<EOS
<div class="row border highlight-background pt-3 pb-3 pl-3">
    <div class="col-12">
        Please present the following QrCOde to your doctor.<br />
        It will provide him an access to all your records. 
        <br /><br />
    </div>
    <div class="margin-0-auto">
        $qrCode
    </div>
</div>
EOS;

        return $html;
    }

    public function outputTitle() {
        return 'Access Delegation';
    }

    /**
     * Return the CSS class for the content of the page.
     *
     * @return mixed
     */
    public function cssClassForContent()
    {
        return '';
    }

    /**
     * Render for access delegation form.
     *
     * @return string
     *   The html.
     */
    public function renderAccessDelegationForm() {
        $fieldDoctorName = $this->renderFieldDoctorName();
        $fieldDoctorSpeciality = $this->renderFieldDoctorSpeciality();
        $fieldDelegationTime = $this->renderFieldDelegationTime();
        $starIsMandatory = $this->renderStarIsMandatory();
        $submitButton = $this->renderSubmitButton('Submit');
        $terminateAccessButton = $this->renderTerminateAccessButton();

        $html = <<<EOS
<form action="accessDelegation.html" id="access_delegation" method="post">

    $fieldDoctorName
    
    $fieldDoctorSpeciality
    
    $fieldDelegationTime
    
    $starIsMandatory
    
    $submitButton    
    $terminateAccessButton
        
</form>
EOS;
        return $html;
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
        if (count($post) > 0 ) {
            return !($post['doctor_name'] === ''
                || $post['doctor_speciality'] === ''
                || $post['delegation_time'] === '');
        }
        else {
            return TRUE;
        }
    }
}