<?php

namespace HealthChain\modules\traits;

trait QrCodeTrait
{
    public function generateQrCode($userKey) {
        global $directory;
        $path = urlencode($_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$directory.'/?q=login__'.$userKey);
        return base64_encode(file_get_contents('https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl='.$path.'&choe=UTF-8'));
    }

    public function displayQrCode($qrCode) {
        return '<img src="data:image/png;base64, '.$qrCode.'">';
    }
}