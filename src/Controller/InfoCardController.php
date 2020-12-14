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
        if(!$currentShift)
            return $this->json('', 404);

        return $this->json([
            'value' => $currentShift->getPeople()->getFio(),
            'subtitle' => 'Смена № ' . $currentShift->getNumber()
            ]);
        
    }

    /**
     * @Route("/volumeBoardsCurrentShift", name="volumeBoardsCurrentShift")
     */
    public function getVolumeBoards(ShiftRepository $shiftRepository, TimberRepository $timberRepository)
    {
        $currentShift = $shiftRepository->getCurrentShift();
        if(!$currentShift)
            return $this->json('', 404);

        $startDate = new DateTime($currentShift->getStart());
        $endDate = new DateTime();
        $period = new DatePeriod($startDate, new DateInterval('P1D'), $endDate); 
        $volumeBoards = number_format((float)$timberRepository->getVolumeBoardsByPeriod($period), 3, ',') . ' м3';
        return $this->json([
            'value' => $volumeBoards,
            ]);
        
    }

    /**
     * @Route("/countTimberCurrentShift", name="countTimberCurrentShift")
     */
    public function getCountTimber(ShiftRepository $shiftRepository, TimberRepository $timberRepository)
    {
        $currentShift = $shiftRepository->getCurrentShift();
        if(!$currentShift)
            return $this->json('', 404);

        $startDate = new DateTime($currentShift->getStart());
        $endDate = new DateTime();
        $period = new DatePeriod($startDate, new DateInterval('P1D'), $endDate); 
        $countTimber = $timberRepository->getCountTimberByPyeriod($period) . ' шт.';
        return $this->json([
            'value' => $countTimber,
            ]);
    }

    /**
     * @Route("/lastDowntime", name="lastDowntime")
     */
    public function getLastDowntime(DowntimeRepository $downtimeRepository)
    {
        $lastDowntime = $downtimeRepository->getlastDowntime();
        if(!$lastDowntime)
            return $this->json('', 404);

        $cause = $lastDowntime->getCause();
        $place = $lastDowntime->getPlace();

        $startTime = $lastDowntime->getDrec();
        $endTime = $lastDowntime->getFinish();
        $nowTime = new DateTime();
        $duration = $endTime ? $endTime->diff($startTime, true)->format('%H:%I:%S') : 'Продолжается(' . $nowTime->diff($startTime, true)->format('%H:%I:%S') . ')';
        return $this->json([
            'value' => $cause->getName(),
            'subtitle' => $duration 
            ]);
    }
}
