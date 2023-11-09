<?php

namespace Visiosoft\SiteModule\Services;

class CheckSsl
{
    protected $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function handle()
    {
        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3000);

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $verify = $httpcode == 200 ? true : false;

        return $verify;
    }
}