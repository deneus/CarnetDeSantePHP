<?php

namespace HealthChain\modules\traits;

trait QrCodeTrait
{
    public function generateQrCode($userKey) {
        global $directory;
        $path = urlencode($_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$directory.'/?q=login__'.$userKey);

        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );

        return base64_encode(file_get_contents('https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl='.$path.'&choe=UTF-8'
            ,false, stream_context_create($arrContextOptions)));
    }

    public function displayQrCode($qrCode) {
        return '<img src="data:image/png;base64, '.$qrCode.'">';
    }
}