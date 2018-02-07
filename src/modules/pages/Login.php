<?php

namespace HealthChain\modules\pages;
use HealthChain\interfaces\ApplicationView;
use HealthChain\layout\LayoutTrait;
use HealthChain\modules\classes\User;
use HealthChain\modules\traits\PostTrait;

class Login implements ApplicationView
{
    use LayoutTrait;
    use PostTrait;

    /**
     * Generate the header html to output.
     *
     * @return mixed
     *   The HTML to output.
     */
    public function outputHtmlHeader()
    {
        return $this->generateHeader('Health Booklet - Login.');
    }

    /**
     * Generate the content html to output.
     *
     * @return String
     *   The HTML to output.
     */
    public function outputHtmlContent()
    {
        $html = '<form action="loginPost.html" method="post">';
        $html .= '<ul>';
        $html .= '<li><label for="login">Enter your key: </label><input type="password" id="login" name="login" placeholder="Your key" /></li>';
        $html .= '<li><input type="submit" /></li>';
        $html .= '</ul>';
        $html .= '</form>';
        return $html;
    }

    public function loginPost($post)
    {
        //TODO: Add proper error management
        $loggedIn = false;
        if(isset($post['login'])){
            $user = new User();
            $user->login($post['login']);
        }
    }

    public function outputTitle()
    {
        return 'Login';
    }
}