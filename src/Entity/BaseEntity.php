<?php

declare(strict_types=1);

namespace App\Entity;

class BaseEntity
{
    public const DATE_FORMAT_DB = 'Y-m-d\TH:i:sP';
    public const DATETIME_FOR_FRONT = 'd.m.Y H:i:s';
    public const TIME_FOR_FRONT = 'H:i:s';
    public const TIME_FORMAT_FOR_INTERVAL = '%H:%I:%S';
}