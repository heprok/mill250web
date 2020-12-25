<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\BaseEntity;
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

        $volumeBoards = number_format($timberRepository->getVolumeBoardsByPeriod($currentShift->getPeriod()), BaseEntity::PRECISION_FOR_FLOAT) . ' м3';
        return $this->json([
            'value' => $volumeBoards,
            'color' => 'info'
        ]);
    }

    /**
     * @Route("/countTimber/{duration}", requirements={"duration"="today|currentShift"}, name="countTimber")
     */
    public function getCountTimber(string $duration, ShiftRepository $shiftRepository, TimberRepository $timberRepository)
    {
        switch ($duration) {
            case 'today':
                $period = BaseEntity::getPeriodToday();
                break;

            case 'currentShift':
                $currentShift = $shiftRepository->getCurrentShift();
                if (!$currentShift)
                    return $this->json(['value' => 0, 'color' => 'error'], 404);
                $period = $currentShift->getPeriod();
                break;
        }

        $countTimber = $timberRepository->getCountTimberByPeriod($period) . ' шт.';
        return $this->json([
            'value' => $countTimber,
            'color' => 'info'
        ]);
    }

    /**
     * @Route("/volumeTimber/{duration}", requirements={"duration"="today|currentShift"}, name="volumeTimber")
     */
    public function getVolumeTimber(string $duration, ShiftRepository $shiftRepository, TimberRepository $timberRepository)
    {
        switch ($duration) {
            case 'today':
                $period = BaseEntity::getPeriodToday();
                break;

            case 'currentShift':
                $currentShift = $shiftRepository->getCurrentShift();
                if (!$currentShift)
                    return $this->json(['value' => 0, 'color' => 'error'], 404);
                $period = $currentShift->getPeriod();
                break;
        }

        $volumeTimber = number_format($timberRepository->getVolumeTimberByPeriod($period), BaseEntity::PRECISION_FOR_FLOAT) . ' м3';
        return $this->json([
            'value' => $volumeTimber,
            'color' => 'info'
        ]);
    }

    /**
     * @Route("/lastDowntime", name="lastDowntime")
     */
    public function getLastDowntime(DowntimeRepository $downtimeRepository)
    {
        $lastDowntime = $downtimeRepository->getLastDowntime();
        if (!$lastDowntime)
            return $this->json(['value' => '', 'color' => 'error'], 404);

        $cause = $lastDowntime->getCause();

        $startTime = $lastDowntime->getDrec();
        $endTime = $lastDowntime->getFinish();
        $nowTime = new DateTime();
        // BaseEntity::intervalToString()
        $duration = $endTime ? BaseEntity::intervalToString($endTime->diff($startTime, true)) : 'Продолжается(' . BaseEntity::intervalToString($nowTime->diff($startTime, true)) . ')';
        return $this->json([
            'value' => $cause ? $cause->getName() : '',
            'subtitle' => $duration . '. C ' . $startTime->format(BaseEntity::TIME_FOR_FRONT . '(d.m)') . ' по ' . ($endTime ? $endTime->format(BaseEntity::DATETIME_FOR_FRONT . '(d.m)') : 'Н.В.'),
            'color' => 'orange',
        ]);
    }

    /**
     * @Route("/summaryDay/{start}...{end}", name="summaryDay")
     */
    public function getSummaryDay(string $start, string $end, ShiftRepository $shiftRepository, DowntimeRepository $downtimeRepository, TimberRepository $timberRepository)
    {
        $startDate = new DateTime($start);
        $endDate = new DateTime($end);
        $period = new DatePeriod($startDate, new DateInterval('P1D'), $endDate);

        $shifts = $shiftRepository->findByPeriod($period);
        if (!$shifts)
            return $this->json('Нет смен за заданный день', 404);

        $result['summary'] = ['volumeBoards' => 0, 'downtime' => new DateTime('00:00')];
        foreach ($shifts as $key => $shift) {
            $result['shifts'][$key]['name'] = 'Смена №' . $shift->getNumber();

            $result['shifts'][$key]['volumeBoards'] = $timberRepository->getVolumeBoardsByPeriod($shift->getPeriod());
            $result['shifts'][$key]['downtime'] = $downtimeRepository->getTotalDowntimeByPeriod($shift->getPeriod());
        }
        foreach ($result['shifts'] as $shift) {
            $result['summary']['volumeBoards'] += $shift['volumeBoards'];

            $result['summary']['downtime']->add(BaseEntity::stringToInterval($shift['downtime']));
        }

        $result['summary']['downtime'] = BaseEntity::intervalToString(date_diff(new DateTime('00:00'), $result['summary']['downtime']));
        return $this->json($result);
    }

    /**
     * @Route("/total/{duration}", requirements={"duration"="today|week"}, name="totalTimeDowntime")
     */
    public function getTotalTimeDowntime(DowntimeRepository $downtimeRepository, string $duration)
    {
        switch ($duration) {
            case 'today':
                $period = BaseEntity::getPeriodToday();
                break;
            case 'week':
                $startTime = new DateTime('-7 day');
                $startTime->setTime(0, 0, 0);
                $period = new DatePeriod($startTime, new DateInterval('P1D'), new DateTime());
                break;
        }
        $durationTime = $downtimeRepository->getTotalDowntimeByPeriod($period);

        if (!$durationTime)
            return $this->json(['value' => '', 'color' => 'error'], 404);

        return $this->json([
            'value' => $durationTime ?? '',
            // 'subtitle' => $duration . '. C ' . $startTime->format(BaseEntity::TIME_FOR_FRONT . '(d.m)') . ' по ' . $endTime->format(BaseEntity::DATETIME_FOR_FRONT . '(d.m)'),
            'color' => 'primary',
        ]);
    }
}
