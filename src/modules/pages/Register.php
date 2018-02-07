<?php

namespace HealthChain\modules\pages;
use HealthChain\interfaces\ApplicationView;
use HealthChain\layout\LayoutTrait;
use HealthChain\modules\classes\User;

class Register implements ApplicationView
{
    use LayoutTrait;

    const ACTION_DISPLAY_FORM = 'display';
    const ACTION_SUBMIT_FORM = 'submit';

    protected $_action;

    /**
     * Generate the header html to output.
     *
     * @return mixed
     *   The HTML to output.
     */

    public function __construct($action)
    {
        $this->_action = $action;
    }
    public function outputHtmlHeader()
    {
        return $this->generateHeader('Health Booklet - Register.');
    }


    protected function _formRegister()
    {
        $typePatient = User::TYPE_USER_PATIENT;
        $typeDoctor = User::TYPE_USER_DOCTOR;

        $html = <<<EOS
<form action="registerPost.html" method="post" class="registerForm col-md-8 col-lg-6">
    <div class="form-group required ">
        <label for="first_name">First Name *</label>
        <input type="text" class="form-control" id="first_name" name="firstname" placeholder="First Name">
    </div>
    
    <div class="form-group required ">
        <label for="last_name">First Name *</label>
        <input type="text" class="form-control" id="last_name" name="lastname" placeholder="Last Name">
    </div>

    <div class="form-group required ">
        <label for="last_name">Date of birth</label>
        <input type="text" class="form-control" id="dob" name="dob" placeholder="Date of Birth">
    </div>
    
    <div class="form-group required ">
        <label for="type">Type of registration *</label>
        <select class="form-control custom-select" id="type" name="type">
            <option value="-1">-- Please select --</option>
            <option value="$typePatient">Patient</option>
            <option value="$typeDoctor">Doctor</option>
        </select>
    </div>    

    <div class="form-group required ">
        <label for="pass_phrase">Your password *</label>
        <input type="password" class="form-control" id="pass_phrase" name="passphrase" placeholder="Your password">
    </div>

    <div>
        <i>Fields marked with a (*) are mandatory.</i>
        <br /><br />
    </div>
    
    <div class="text-right">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
    
    <div class="text-center">
        <br />
        You already have an account? Please <a href="/HealthChainPHP/?q=login"">sign in</a>.
    </div>
</form>
EOS;

        return $html;

    }

    /**
     * Generate the content html to output.
     *
     * @return String
     *   The HTML to output.
     */
    public function outputHtmlContent()
    {
        switch($this->_action){
            case self::ACTION_SUBMIT_FORM:
                return $this->registerPost();
            break;
            case self::ACTION_DISPLAY_FORM:
            default:
                return $this->_formRegister();
            break;
        }

    }

    public function registerPost()
    {
        try{
            $user = new User();
            //TODO: Adding form validator in here
            //TODO: Sanitize post
            $passphrase = $_POST['passphrase'];
            $html = '<h2> Thank you for registering into HealthChain </h2>';
            $html .= 'Your access: ';
            $html .= $user->register($passphrase);
            return $html;
        }
        catch (\Exception $e){
            echo 'An error occured when trying to create the account. Please retry later';
        }
    }

    public function outputTitle() {
        return 'Register';
    }

    public function cssClassForContent() {
        return 'bg-info';
    }
}