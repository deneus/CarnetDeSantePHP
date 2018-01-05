<?php

namespace HealthChain\layout;

trait LayoutTrait
{

    /**
     * Generate the header of the site.
     *
     * @param $title
     *   The title.
     *
     * @return string
     *   The html.
     */
    public function generateHeader($title)
    {
        $html = <<<EOT
        <div class="row text-center w-100">
    <h1 class="w-100">$title</h1>    
</div>
    
EOT;
        return $html;
    }

}
