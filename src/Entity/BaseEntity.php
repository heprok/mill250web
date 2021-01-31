<?php

declare(strict_types=1);

namespace App\Entity;

use DateInterval;
use DatePeriod;
use DateTime;

class BaseEntity
{
    public const DATE_FORMAT_DB = 'Y-m-d\TH:i:sP';
    public const DATE_SECOND_FORMAT_DB = 'Y-m-d H:i:s.u';
    public const DATETIME_FOR_FRONT = 'd.m.Y H:i:s';
    public const TIME_FOR_FRONT = 'H:i:s';
    public const INTERVAL_TIME_FROMAT = '%H:%I:%S';
    public const INTERVAL_DAY_TIME_FROMAT = '%d д. ' . self::INTERVAL_TIME_FROMAT;
    public const INTERVAL_MOUNT_DAY_TIME_FROMAT = '%m м. ' . self::INTERVAL_DAY_TIME_FROMAT;
    public const START_DAY_TIME_STRING = "08:00:00";
    public const START_DAY_TIME_INTREVAL = 'PT8H'; 
    public const PRECISION_FOR_FLOAT = 3;


    static public function stringToInterval(?string $duration): DateInterval
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
    static public function getStartDayInterval() : DateInterval
    {
        return new DateInterval(self::START_DAY_TIME_INTREVAL);
    }

    /**
     * Возращает DatePeriod, в зависимсоти от времени начала дня (START_DAY_TIME_STRING)
     * Если указан аргумент, то возращает от сегодняшего числа до - $countDay
     *
     * @param integer $countDay кол-во дней за указанный период
     * @return DatePeriod
     */
    static public function getPeriodForDay(int $countDay = 0): DatePeriod
    {
        $nowTime = new DateTime();
        $intervalStartDay = self::getStartDayInterval();
        $startTime = $nowTime->format('H') >= $intervalStartDay->h ? $nowTime : $nowTime->sub(new DateInterval('P1D'));
            
        $startTime->setTime($intervalStartDay->h, $intervalStartDay->m, $intervalStartDay->s);
        
        $endTime = clone $startTime;

        $countDay == 0 ? $endTime->add(new DateInterval('P1D')) : $startTime->sub(new DateInterval('P' . $countDay . 'D'));  
            
        $period = new DatePeriod($startTime, new DateInterval('P1D'), $endTime);
        return $period;
    }

    // static public function getPeriodForDay(int $countDay) : DatePeriod
    // {
    //     $nowTime = new DateTime();
    //     $intervalStartDay = self::getStartDayInterval();
    //     $startTime = $nowTime->format('H') >= $intervalStartDay->h ? $nowTime : $nowTime->sub(new DateInterval('P1D'));

    //     $startTime->setTime($intervalStartDay->h, $intervalStartDay->m, $intervalStartDay->s);

    //     $endTime = clone $startTime;

    //     $startTime->sub(new DateInterval('P' . $countDay . 'D'));

    //     $period = new DatePeriod($startTime, new DateInterval('P1D'), $endTime);
    // }
}
