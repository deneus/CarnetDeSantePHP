<?php

namespace HealthChain\modules\pages;
use HealthChain\interfaces\ApplicationView;
use HealthChain\modules\classes\User;
use HealthChain\modules\traits\PostTrait;

class Login implements ApplicationView
{
    use PostTrait;

    /**
     * Generate the content html to output.
     *
     * @return String
     *   The HTML to output.
     */
    public function outputHtmlContent()
    {
        global $directory;
        $html = <<<EOS
<form action="loginPost.html" method="post" class="loginForm col-md-8 col-lg-6" autocomplete="off">
    <h2 class="text-center pb-3">Log in</h2>

    <div class="form-group required row">
        <label for="login" class="col-12 offset-1 mt-2">Please enter your Health Booklet key to sign in.<br/><br/></label>
        <label for="login" class="col-2 text-center mt-2"><i class="fa fa-key"></i></label>
        <input type="password" class="form-control col-9" id="login" name="login" placeholder="Enter your key">
    </div>
    
     <div class="row">
        <div class="col-10 offset-1">
            <br />
            <button type="submit" class="btn btn-primary col-12">Register</button>
        </div>
    </div>
    
    <div class="row">
        <div class="col-10 offset-1 text-center">
            <br />
            You don't have an account? Please <a href="$directory/register.html">register</a>.
        </div>
    </div>
</form>    
EOS;

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

    public function cssClassForContent() {
        return 'bg-info';
    }
}