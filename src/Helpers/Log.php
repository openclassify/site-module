<?php

namespace Visiosoft\SiteModule\Helpers;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Log
{

    /**
     * @param string $stream
     * @param $exception
     * @return void
     */
    public function createLog(string $stream, $exception): void
    {
        $log = new Logger($stream);
        $log->pushHandler(new StreamHandler(storage_path('logs/' . $stream . '.log')), Logger::ERROR);
        $log->error($exception);
    }
}