<?php

namespace HealthChain\modules;

use HealthChain\interfaces\ApplicationView;
use HealthChain\layout\LayoutTrait;
use HealthChain\modules\classes\Entry;

class newEntry implements ApplicationView
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
        return $this->generateHeader('Health Booklet - Add new entry.');
    }

    /**
     * Generate the content html to output.
     *
     * @return String
     *   The HTML to output.
     */
    public function outputHtmlContent()
    {
        $entry = new Entry();
        return $entry->renderAddForm();
    }
}