<?php

namespace HealthChain\modules\pages;
use HealthChain\interfaces\ApplicationView;
use HealthChain\layout\LayoutTrait;
use HealthChain\modules\classes\User;
use HealthChain\modules\traits\PostTrait;

class Register implements ApplicationView
{
    use LayoutTrait;
    use PostTrait;

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
<form action="registerPost.html" method="post" class="registerForm col-md-8 col-lg-6" autocomplete="off">
    <h2 class="text-center pb-3">Create an account</h2>

    <div class="form-group required row">
        <label for="full_name" class="col-2 text-center mt-2"><i class="fa fa-user-plus"></i> *</label>
        <input type="text" class="form-control col-9" id="full_name" name="fullname" placeholder="First Name Last Name">
    </div>
    
    <div class="form-group required row">
        <label for="email" class="col-2 text-center mt-2"><i class="fa fa-envelope"></i> *</label>
        <input type="text" class="form-control col-9" id="email" name="email" placeholder="email">
    </div>

    <div class="form-group required row">
        <label for="dob" class="col-2 text-center mt-2"><i class="fa fa-calendar-alt"></i> *</label>
        <input type="text" class="form-control col-9" id="dob" name="dob" placeholder="Date of Birth">
    </div>
    
    <div class="form-group required row">
        <label for="type" class="col-2 text-center mt-2"><i class="fa fa-user"></i> / <i class="fa fa-user-md"></i> *</label>
        <select class="form-control custom-select col-9" id="type" name="type">
            <option value="-1">-- Please select --</option>
            <option value="$typePatient">Patient</option>
            <option value="$typeDoctor">Doctor</option>
        </select>
    </div>    

    <div class="form-group required row">
        <label for="pass_phrase" class="col-2 text-center mt-2"><i class="fa fa-lock"></i> *</label>
        <input type="password" class="form-control col-9" id="pass_phrase" name="passphrase" placeholder="Your password">
    </div>

    <div class="row">
        <div class="col-10 offset-1">
            <i>Fields marked with a (*) are mandatory.</i>
            <br /><br />
        </div>
    </div>
    
    <div class="row">
        <div class="col-10 offset-1">
            <button type="submit" class="btn btn-primary col-12">Register</button>
        </div>
    </div>
    
    <div class="row">
        <div class="col-10 offset-1 text-center">
            <br />
            You already have an account? Please <a href="/HealthChainPHP/login.html">sign in</a>.
        </div>
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
            $post = $this->sanitize($_POST);
            $passphrase = $post['passphrase'];

            // Generate user key.
            $userKey = $user->register($passphrase);

            /*
             * Generate QRCode.
             * I used Google api to do it, for security reason, I need to process it as follow.
             */
            $qrCode = base64_encode(file_get_contents('https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl='.$userKey.'&choe=UTF-8'));

            $html = <<<EOS
<div class="registerPost col-md-8 col-lg-6">
    <h2 class="text-center pb-3">Registration complete</h2>
    
    <div class="row">
        <div class="col-10 offset-1">
            <div class="row">
                <p>Thank you for registering into your Health Booklet.</p>
                <p>Your access: ???</p>
                <p>Your login is an unique identifier.</p>
                
                <p> Your backup code is used if you lost your password.
                <br /> Those information are private and should never be shared with everyone.
                <br /> We will never ask you such information by email or in the phone.
                <br /> Please save carefully the following information.</p>
            </div>
            
            <div class="row">
                <label for="login" class="col-2 text-center mt-2"><i class="fa fa-key"></i></label>
                <div id="userKey" class="col-10" style="word-wrap: break-word;"><b>$userKey</b></div>
                <button id="copyToClipboardBtn" class="btn btn-success col-12" data-clipboard-target="#userKey">Copy to clipboard</button>
            </div>

            <div class="row">
                <div>
                    <br/>Your key is also available as a QRCode for storage purpose.
                </div>
                <div class="qrcode">
                    <img src="data:image/png;base64, $qrCode">
                </div>
            </div>
            
    <div class="row">
        <div class="col-10 offset-1 text-center">
            <br />
            Now it's time to <a href="/HealthChainPHP/login.html">log in</a>.
        </div>
    </div>

        </div>
    </div>
    
    
</div>
EOS;

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