<?php

namespace HealthChain\interfaces;

interface ApplicationView {

    /**
     * Generate the content html to output.
     *
     * @return String
     *   The HTML to output.
     */
    public function outputHtmlContent() ;

    /**
     * Generate the title of the page.
     *
     * @return String
     *   The HTML to output.
     */
    public function outputTitle() ;

    /**
     * Return the CSS class for the content of the page.
     *
     * @return mixed
     */
    public function cssClassForContent();

    /**
     * Return the CSS class for the banner > display a background image.
     *
     * @return mixed
     */
    public function cssClassForBanner();
}