<?php

namespace Visiosoft\SiteModule\Helpers;

abstract class UpdateStatus
{
    const STARTED = 0;
    const WAITING = 1;
    const UPDATED = 2;
    const FAILED = 3;

    private static array $aliases = [
        self::STARTED => 'Started',
        self::WAITING => 'Updating',
        self::UPDATED => 'Updated',
        self::FAILED => 'Failed',
    ];

    /**
     * @param $status
     * @return string|null
     */
    public static function getUpdateStatus($status): ?string
    {
        if (isset(self::$aliases[$status])) {
            return self::$aliases[$status];
        }
        return null;
    }

}