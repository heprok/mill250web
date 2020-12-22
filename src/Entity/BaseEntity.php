<?php

declare(strict_types=1);

namespace App\Entity;

use DateInterval;

class BaseEntity
{
    public const DATE_FORMAT_DB = 'Y-m-d\TH:i:sP';
    public const DATE_SECOND_FORMAT_DB = 'Y-m-d H:i:s.u';
    public const DATETIME_FOR_FRONT = 'd.m.Y H:i:s';
    public const TIME_FOR_FRONT = 'H:i:s';
    public const INTERVAL_TIME_FROMAT = '%H:%I:%S';
    public const INTERVAL_DAY_TIME_FROMAT = '%d д. ' . self::INTERVAL_TIME_FROMAT;
    public const INTERVAL_MOUNT_DAY_TIME_FROMAT = '%m м. ' . self::INTERVAL_DAY_TIME_FROMAT;


    static public function stringToInterval(string $duration): DateInterval
    {
        $reg = "/(?'month'\d*?) *м*\.* *(?'day'\d)* *д*\.* *(?'hours'\d\d)+:(?'minutes'\d\d)+:(?'seconds'\d\d)/u";
        $matches = [];
        $intervalstr = 'P';

        preg_match($reg, $duration, $matches);
        if ($matches['month'] != '') $intervalstr .= $matches[1] . 'M';
        if ($matches['day'] != '') $intervalstr .= $matches[2] . 'D';
        if ($matches['hours'] != '') $intervalstr .= 'T' . $matches[3] . 'H';
        if ($matches['minutes'] != '') $intervalstr .=  $matches[4] . 'M';
        if ($matches['seconds'] != '') $intervalstr .=  $matches[5] . 'S';

        return new DateInterval($intervalstr);
        //00:03:00 
        //1 д. 00:03:00
        //1 м. 1 д. 00:03:00
    }

    static public function intervalToString(DateInterval $dateInterval): string
    {
        if ($dateInterval->m > 0)
            $dateInterval = $dateInterval->format(BaseEntity::INTERVAL_MOUNT_DAY_TIME_FROMAT);
        elseif ($dateInterval->d > 0)
            $dateInterval = $dateInterval->format(BaseEntity::INTERVAL_DAY_TIME_FROMAT);
        else
            $dateInterval = $dateInterval->format(BaseEntity::INTERVAL_TIME_FROMAT);

        return $dateInterval;
    }
}
