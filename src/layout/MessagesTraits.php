<?php

namespace HealthChain\layout;

trait MessagesTraits
{

    /**
     * Helper to generate a success message.
     *
     * @param string $message
     *   The message to display.
     *
     * @return string
     *   The generate html message.
     */
    public function generateSuccessMessage($message) {
        $html = <<<EOT
<div class="alert alert-success">
    $message
</div>
EOT;

        return $html;
    }

    /**
     * Helper to generate a failure message.
     *
     * @param string $message
     *   The message to display.
     *
     * @return string
     *   The generate html message.
     */
    public function generateFailMessage($message = null) {
        if ($message === NULL) {
            $message = 'A problem occured, please contact the administrator.';
        }

        $extraCss = '';
        if(!isset($_SESSION['user'])) {
            $extraCss = 'col-md-8 col-lg-6 margin-0-auto mb-3';
        }

        $html = <<<EOS
<div class="alert alert-danger $extraCss">
    $message
</div>
EOS;

        return $html;
    }

    /**
     * Helper to generate a info message.
     *
     * @param string $message
     *   The message to display.
     *
     * @return string
     *   The generate html message.
     */
    public function generateInfoMessage($message) {
        $html = <<<EOT
<div class="alert alert-info">
    $message
</div>
EOT;

        return $html;
    }

    /**
     * Helper to generate a warning message.
     *
     * @param string $message
     *   The message to display.
     *
     * @return string
     *   The generate html message.
     */
    public function generateWarningMessage($message) {
        $html = <<<EOT
<div class="alert alert-warning">
    $message.
</div>
EOT;

        return $html;
    }
}
