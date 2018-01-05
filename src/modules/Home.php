<?php

namespace HealthChain\modules;

use HealthChain\interfaces\ApplicationView;

class Home implements ApplicationView
{

    private $ipfs;

    public function __construct()
    {
        global $ipfs;
        $this->ipfs = $ipfs;
    }

    public function outputHtml()
    {
        $html = '<table class="table table-hover table-striped ">';
        $html .= '<thead class="thead-dark">
            <tr>
                <th scope="col">Who</th>
                <th scope="col">Date</th>
                <th scope="col">Comment</th>
            </tr>
         </thead>';
        $html .= '<tbody>';

        $testHash = $this->generateTestHashes();

        foreach ($testHash as $hash) {
            $who = 'TBD';
            $date = new \DateTime();
            // Remove 'a831rwxi1a3gzaorw1w2z49dlsor' at the end of the cat.
            $comment = $this->ipfs->cat($hash);
            $lastSpace = strrpos($comment, " ");
            $comment = substr($comment, 0, $lastSpace);  //on découpe à la fin du dernier mot

            $html .= '<tr>';
            $html .= '<td>' . $who . '</td>';
            $html .= '<td>' . $date->format('d/m/o') . '</td>';
            $html .= '<td>' . $comment . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody>';
        $html .= '</table>';

        return $html;
    }

    public function generateTestHashes()
    {
        $hash = [];
        $hash[] = $this->ipfs->add("<u>My</u> message 1");
        $hash[] = $this->ipfs->add("<i>My</i> message 2");
        $hash[] = $this->ipfs->add("My message 3");
        $hash[] = $this->ipfs->add("My message 4");
        $hash[] = $this->ipfs->add("My message 5");
        $hash[] = $this->ipfs->add("My message 6");
        $hash[] = $this->ipfs->add("My message 7");

        return $hash;
    }
}


?>