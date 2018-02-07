<?php

namespace HealthChain\interfaces;

interface ApplicationView {

    /**
     * Generate the header html to output.
     *
     * @return mixed
     *   The HTML to output.
     */
    public function outputHtmlHeader();

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
}