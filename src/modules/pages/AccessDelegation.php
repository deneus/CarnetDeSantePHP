<?php

namespace HealthChain\modules\pages;

use HealthChain\interfaces\ApplicationView;

class AccessDelegation implements ApplicationView
{

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
        // TODO: Implement cssClassForContent() method.
    }
}