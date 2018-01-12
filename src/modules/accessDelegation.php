<?php

namespace HealthChain\modules;

use HealthChain\interfaces\ApplicationView;
use HealthChain\layout\LayoutTrait;

class accessDelegation implements ApplicationView
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
        return $this->generateHeader('Health Booklet - Delegate access.');
    }

    /**
     * Generate the content html to output.
     *
     * @return String
     *   The HTML to output.
     */
    public function outputHtmlContent()
    {
        // TODO: Implement outputHtmlContent() method.
    }
}