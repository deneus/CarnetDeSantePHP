<?php

namespace HealthChain\modules\pages;

use HealthChain\interfaces\ApplicationView;
use HealthChain\layout\MessagesTraits;
use HealthChain\modules\classes\User;
use HealthChain\modules\traits\FormTrait;

class AccessDelegation implements ApplicationView
{

    use MessagesTraits;
    use FormTrait;

    /**
     * Generate the content html to output.
     *
     * @return String
     *   The HTML to output.
     */
    public function outputHtmlContent()
    {
        if(User::isUserDoctor()) {
            return $this->generateFailMessage('You are not authorised to access this page.');
        }

        $html = $this->renderAccessDelegationForm();
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
}