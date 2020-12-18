<?php

declare(strict_types=1);

namespace App\Entity;

class BaseEntity
{
    public const DATE_FORMAT_DB = 'Y-m-d\TH:i:sP';
    public const DATETIME_FOR_FRONT = 'd.m.Y H:i:s';
    public const TIME_FOR_FRONT = 'H:i:s';
    public const INTERVAL_TIME_FROMAT = '%H:%I:%S';
    public const INTERVAL_DAY_TIME_FROMAT = '%d д. ' . self::INTERVAL_TIME_FROMAT;
    public const INTERVAL_MOUNT_DAY_TIME_FROMAT = '%m м. ' . self::INTERVAL_DAY_TIME_FROMAT;

}