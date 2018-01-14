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
    $message.
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
            $message = 'A problem occured, please contact the administrator';
        }

        $html = <<<EOT
<div class="alert alert-danger">
    $message.
</div>
EOT;

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
    $message.
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
