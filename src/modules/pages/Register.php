<?php

namespace HealthChain\modules\pages;
use HealthChain\interfaces\ApplicationView;
use HealthChain\layout\MessagesTraits;
use HealthChain\modules\classes\User;
use HealthChain\modules\traits\PostTrait;
use HealthChain\modules\traits\QrCodeTrait;

class Register implements ApplicationView
{
    use PostTrait;
    use MessagesTraits;
    use QrCodeTrait;

    const ACTION_DISPLAY_FORM = 'display';
    const ACTION_SUBMIT_FORM = 'submit';

    protected $_action;
    protected $userKey;

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

    /**
     * Render the registration form.
     *
     * @return string
     *   The html.
     */
    protected function renderRegistrationForm()
    {
        global $directory;

        $typePatient = User::TYPE_USER_PATIENT;
        $typeDoctor = User::TYPE_USER_DOCTOR;

        $html = <<<EOS
<form action="registerPost.html" method="post" class="registerForm col-md-8 col-lg-6" autocomplete="off">
    <h2 class="text-center pb-3">Create an account</h2>

    <div class="form-group required row">
        <label for="full_name" class="col-2 text-center mt-2"><i class="fa fa-user-plus"></i> *</label>
        <input type="text" class="form-control col-9" id="full_name" name="fullName" placeholder="First Name Last Name">
    </div>
    
    <div class="form-group required row">
        <label for="email" class="col-2 text-center mt-2"><i class="fa fa-envelope"></i> *</label>
        <input type="text" class="form-control col-9" id="email" name="email" placeholder="email">
    </div>

    <div class="form-group required row">
        <label for="dob" class="col-2 text-center mt-2"><i class="fa fa-calendar-alt"></i> *</label>
        <input type="text" class="form-control col-9" id="dob" name="dob" placeholder="Date of Birth">
    </div>
    
    <!--
    <div class="form-group required row">
        <label for="type" class="col-2 text-center mt-2"><i class="fa fa-user"></i> / <i class="fa fa-user-md"></i> *</label>
        <select class="form-control custom-select col-9" id="type" name="type">
            <option value="-1">-- Please select --</option>
            <option value="$typePatient">Patient</option>
            <option value="$typeDoctor">Doctor</option>
        </select>
    </div>    
    -->
    <input type="hidden" name="type" value="$typePatient">
    
    <div class="form-group required row">
        <label for="pass_phrase" class="col-2 text-center mt-2"><i class="fa fa-lock"></i> *</label>
        <input type="password" class="form-control col-9" id="pass_phrase" name="passPhrase" placeholder="Your password">
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
            You already have an account? Please <a href="$directory/login.html">sign in</a>.
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
     *
     * @throws \Exception
     */
    public function outputHtmlContent()
    {
        $post = $this->sanitize($_POST);
        $html = $this->processPost($post);

        switch($this->_action){
            case self::ACTION_SUBMIT_FORM:
                $html .= $this->renderRegistrationComplete();
            break;
            case self::ACTION_DISPLAY_FORM:
            default:
                $html .= $this->renderRegistrationForm();
            break;
        }
        return $html;
    }

    /**
     * Process the post.
     *
     * @param $post
     *   The sanitized POST.
     *
     * @return string
     *   The html.
     */
    public function processPost($post)
    {
        if ($_GET['q'] === 'registerPost') {
            try{

                $postIntegrity = $this->verifyPostIntegrity($post);
                if ($postIntegrity !== NULL) {
                    return $postIntegrity;
                }

                // Generate user key.
                $user = new User();
                $user->createUser($post);
                $this->userKey = $user->register($user->passPhrase);

                /*
                 * Generate QRCode.
                 * I used Google api to do it, for security reason, I need to process it as follow.
                 * @todo denis: the QrCode is a path to the site + the key, not just the key.
                 */
                $this->qrCode = $this->generateQrCode($this->userKey);

                $user->key = $this->qrCode;
                $user->storeUser();
            }
            catch (\Exception $e){
                echo 'An error occurred when trying to create the account. Please retry later';
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function outputTitle() {
        return 'Register';
    }

    /**
     * {@inheritdoc}
     */
    public function cssClassForContent() {
        return 'bg-info';
    }

    /**
     * Render the page registration complete.
     *
     * @return string
     *   The html.
     */
    public function renderRegistrationComplete() {
        global $directory;
        $qrCode = $this->displayQrCode($this->qrCode);

        $html = <<<EOS
<div class="registerPost col-md-8 col-lg-6">
    <h2 class="text-center pb-3">Registration complete</h2>
    
    <div class="row">
        <div class="col-10 offset-1">
            <div class="row">
                <p>Thank you for registering into your Health Booklet.</p>
                <p>Your login is an unique identifier.</p>
                
                <p><!-- Your backup code is used if you lost your password.
                <br /> --> Those information are private and should never be shared with everyone.
                <br /> We will never ask you such information by email or in the phone.
                <br /> Please save carefully the following information.</p>
            </div>
            
            <div class="row border highlight-background pt-2 pb-2">
                <label for="login" class="col-2 text-center mt-2"><i class="fa fa-key"></i></label>
                <div id="userKey" class="col-10" style="word-wrap: break-word;"><b>$this->userKey</b><br /><br></div>
                <br />
                <br />
                <button id="copyToClipboardBtn" class="btn btn-success col-10 offset-1" data-clipboard-target="#userKey">Copy to clipboard</button>
            </div>

            <div class="row">
                <div>
                    <br/>Your key is also available as a QRCode for storage purpose.
                </div>
                <div class="margin-0-auto">
                    $qrCode
                </div>
            </div>
            
    <div class="row">
        <div class="col-10 offset-1 text-center">
            <br />
            Now it's time to <a href="$directory/login.html">log in</a>.
        </div>
    </div>

        </div>
    </div>
    
    
</div>
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
        return !($post['fullName'] === ''
            || $post['email'] === ''
            || $post['dob'] === ''
            || $post['type'] === ''
            || $post['passPhrase'] === '');
    }


    /**
     * Test if the date has a valid format.
     *
     * @param $date
     *   The date to test.
     *
     * @return bool
     *   The verification status.
     */
    public function isDateValid($date) {
        try {
            $explode = explode('/', $date);
            $dateTime = new \DateTime();
            $dateTime->format('d/m/Y');
            if (count($explode) !== 3) {
                return FALSE;
            }
            if (!$dateTime->setDate($explode[2], $explode['1'], $explode[0])) {
                return FALSE;
            }
            return TRUE;
        }
        catch (\Exception $e) {
            return FALSE;
        }

    }

    public function verifyPostIntegrity($post) {
        $output = NULL;
        if (!$this->isPostFull($post)) {
            $html = $this->generateFailMessage('All fields are mandatory.');
            $this->_action = self::ACTION_DISPLAY_FORM;
            return $html;
        }
        if (!$this->isDateValid($post['dob'])) {
            $html = $this->generateFailMessage('The date of birth field should follow the format dd/mm/yyyy.');
            $this->_action = self::ACTION_DISPLAY_FORM;
            return $html;
        }

        if (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
            $html = $this->generateFailMessage('The email is in a wrong format.');
            $this->_action = self::ACTION_DISPLAY_FORM;
            return $html;
        }
        return $output;

    }

}