<?php

namespace HealthChain\modules\pages;
use HealthChain\interfaces\ApplicationView;
use HealthChain\layout\LayoutTrait;

class Login implements ApplicationView
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
        //TODO renderAddFormLogin;
    }

    public function outputTitle() {
        return 'Login';
    }

    public function cssClassForContent() {
        return '';
    }
}