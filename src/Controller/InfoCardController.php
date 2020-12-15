<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Downtime;
use App\Repository\ShiftRepository;
use App\Entity\Shift;
use App\Entity\People;
use App\Repository\DowntimeRepository;
use App\Repository\TimberRepository;
use DateInterval;
use DatePeriod;
use DateTime;
use DateTimeInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("api/infocard", name="info_card_")
 */
class InfoCardController extends AbstractController
{

    /**
     * @Route("/currentShift", name="currentShift")
     */
    public function getCurrentShift(ShiftRepository $shiftRepository)
    {
        $currentShift = $shiftRepository->getCurrentShift();
        if (!$currentShift)
            return $this->json(['value' => 'Не начата', 'color' => 'error'], 404);

        return $this->json([
            'value' => $currentShift->getPeople()->getFio(),
            'subtitle' => 'Смена № ' . $currentShift->getNumber(),
            'color' => 'info'
        ]);
    }

    /**
     * @Route("/volumeBoardsCurrentShift", name="volumeBoardsCurrentShift")
     */
    public function getVolumeBoards(ShiftRepository $shiftRepository, TimberRepository $timberRepository)
    {
        $currentShift = $shiftRepository->getCurrentShift();
        if (!$currentShift)
            return $this->json(['value' => 0, 'color' => 'error'], 404);

        $startDate = new DateTime($currentShift->getStart());
        $endDate = new DateTime();
        $period = new DatePeriod($startDate, new DateInterval('P1D'), $endDate);
        $volumeBoards = number_format((float)$timberRepository->getVolumeBoardsByPeriod($period), 3, ',') . ' м3';
        return $this->json([
            'value' => $volumeBoards,
            'color' => 'info'
        ]);
    }

    /**
     * @Route("/countTimberCurrentShift", name="countTimberCurrentShift")
     */
    public function getCountTimber(ShiftRepository $shiftRepository, TimberRepository $timberRepository)
    {
        $currentShift = $shiftRepository->getCurrentShift();
        if (!$currentShift)
            return $this->json(['value' => 0, 'color' => 'error'], 404);

        $startDate = new DateTime($currentShift->getStart());
        $endDate = new DateTime();
        $period = new DatePeriod($startDate, new DateInterval('P1D'), $endDate);
        $countTimber = $timberRepository->getCountTimberByPyeriod($period) . ' шт.';
        return $this->json([
            'value' => $countTimber,
            'color' => 'info'
        ]);
    }

    /**
     * @Route("/lastDowntime", name="lastDowntime")
     */
    public function getLastDowntime(DowntimeRepository $downtimeRepository)
    {
        $lastDowntime = $downtimeRepository->getlastDowntime();
        if (!$lastDowntime)
            return $this->json(['value' => '', 'color' => 'error'], 404);

        $cause = $lastDowntime->getCause();

        $startTime = $lastDowntime->getDrec();
        $endTime = $lastDowntime->getFinish();
        $nowTime = new DateTime();
        $duration = $endTime ? $endTime->diff($startTime, true)->format('%d день %H:%I:%S') : 'Продолжается(' . $nowTime->diff($startTime, true)->format('%d день %H:%I:%S') . ')';
        return $this->json([
            'value' => $cause ?? '',
            'subtitle' => $duration . '. C ' . $startTime->format(Shift::DATE_FOR_FRONT_TIME . '(d.m)') . ' по ' . $endTime->format(Shift::DATE_FOR_FRONT_TIME . '(d.m)') ,
            'color' => 'orange',
        ]);
    }
}
