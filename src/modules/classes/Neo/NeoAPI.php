<?php

namespace HealthChain\modules\classes\Neo;


class NeoAPI
{
     const SERVER_URL = 'http://localhost:8000/';
     const METHOD_POST = 'post';
     const METHOD_GET = 'get';

    public static function call($apiMethod, $method=self::METHOD_GET, $params=[])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::SERVER_URL. $apiMethod);


        if($method == self::METHOD_POST){
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        return $server_output;
    }
}