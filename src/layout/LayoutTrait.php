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
<header class="row bg-info pt-5 pb-5">
    <div class="row text-center w-100">
        <h1 class="w-100">$title</h1>    
    </div>
</header>

<nav class="topnav row no-gutters mb-3 ">
    <div class="col-10 offset-1">
        <a class="" href="/HealthChainPHP/">Home</a>
        <a class="" href="/HealthChainPHP/?q=newEntry">New Entry</a>
        <a class="" href="/HealthChainPHP/?q=accessDelegation">Access Delegation</a>
    </div>
</nav>

        
        

EOT;
        return $html;
    }

}
