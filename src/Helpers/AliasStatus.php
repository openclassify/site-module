<?php

namespace Visiosoft\SiteModule\Helpers;

abstract class AliasStatus
{
    const WAITING = 0;
    const CREATED = 1;
    const CREATE_FAIL = 2;
    const SSL_FAIL = 3;
    const DELETE_FAIL = 4;
    const SSL_STARTED = 5;

    private static array $aliases = [
        self::WAITING => 'WAITING',
        self::CREATED => 'CREATED',
        self::CREATE_FAIL => 'CREATE_FAIL',
        self::SSL_FAIL => 'SSL Installation Failed',
        self::DELETE_FAIL => 'DELETE_FAIL',
        self::SSL_STARTED => 'SSL Installation Started'
    ];

    /**
     * @param $status
     * @return string|null
     */
    public static function getAliasStatus($status): ?string
    {
        if (isset(self::$aliases[$status])) {
            return self::$aliases[$status];
        }
        return null;
    }

}