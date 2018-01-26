<?php

namespace HealthChain\modules\pages;
use HealthChain\interfaces\ApplicationView;
use HealthChain\layout\LayoutTrait;
use HealthChain\modules\classes\User;

class Register implements ApplicationView
{
    use LayoutTrait;
    /**
     * Generate the header html to output.
     *
     * @return mixed
     *   The HTML to output.
     */
    public function outputHtmlHeader()
    {
        return $this->generateHeader('Health Booklet - Register.');
    }


    protected function _formRegister()
    {
        $html = '<form action="registerPost.html" method="post"><ul>';
        $html .= '<li><input type="text" name="firstname" placeholder="First name" /></li>';
        $html .= '<li><input type="text" name="lastname" placeholder="Last name" /></li>';
        $html .= '<li><input type="text" name="dob" placeholder="Date of birth" /></li>';
        $html .= '<li><input type="radio" name="type" value="'.User::TYPE_USER_PATIENT.'"> Patient</li>';
        $html .= '<li><input type="radio" name="type" value="'.User::TYPE_USER_DOCTOR.'"> Doctor</li>';
        $html .= '<li><input type="radio" name="type" placeholder="Date of birth" /></li>';
        $html .= '<li><input type="password" name="passphrase" placeholder="Your password" /></li>';
        $html .= '<ul>';
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
        echo $this->_formRegister();
    }

    public function registerPost()
    {
        try{
            $user = new User();
            $html = '<h2> Thank you for registering into HealthChain </h2>';
            $html .= 'Your access: ';
            echo $html;
            echo $user->register();
        }
        catch (\Exception $e){
            echo 'An error occured when trying to create the account. Please retry later';
        }
    }
}