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
    private function getPeriodForDuration(string $duration, ShiftRepository $shiftRepository) : DatePeriod 
    {
        switch ($duration) {
            case 'today':
                $period = BaseEntity::getPeriodForDay();
                break;
                        
            case 'weekly':
                $period = BaseEntity::getPeriodForDay(7);
                break;

            case 'mountly':
                $period = BaseEntity::getPeriodForDay(30);
                break;

            case 'currentShift':
                $currentShift = $shiftRepository->getCurrentShift();
                if (!$currentShift)
                    return $this->json(['value' => '0', 'color' => 'error'], 404);
                $period = $currentShift->getPeriod();
                break;
        }

        return $period;
    }

    /**
     * @Route("/volumeBoards/{duration}", requirements={"duration"="today|currentShift|mountly|weekly"}, name="volumeBoards")
     */
    public function getVolumeBoards(string $duration, ShiftRepository $shiftRepository, TimberRepository $timberRepository)
    {
        $period = $this->getPeriodForDuration($duration, $shiftRepository);

        $volumeBoards = number_format($timberRepository->getVolumeBoardsByPeriod($period), BaseEntity::PRECISION_FOR_FLOAT) . ' м3';
        return $this->json([
            'value' => $volumeBoards,
            'color' => 'info'
        ]);
    }

    /**
     * @Route("/countTimber/{duration}", requirements={"duration"="today|currentShift|mountly|weekly"}, name="countTimber")
     */
    public function getCountTimber(string $duration, ShiftRepository $shiftRepository, TimberRepository $timberRepository)
    {
        $period = $this->getPeriodForDuration($duration, $shiftRepository);

        $countTimber = $timberRepository->getCountTimberByPeriod($period) . ' шт.';
        return $this->json([
            'value' => $countTimber,
            'color' => 'info'
        ]);
    }

    /**
     * @Route("/volumeTimber/{duration}", requirements={"duration"="today|currentShift|mountly|weekly"}, name="volumeTimber")
     */
    public function getVolumeTimber(string $duration, ShiftRepository $shiftRepository, TimberRepository $timberRepository)
    {
        $period = $this->getPeriodForDuration($duration, $shiftRepository);

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

            $result['shifts'][$key]['volumeBoards'] = round($timberRepository->getVolumeBoardsByPeriod($shift->getPeriod()), BaseEntity::PRECISION_FOR_FLOAT);
            $result['shifts'][$key]['downtime'] = $downtimeRepository->getTotalDowntimeByPeriod($shift->getPeriod());
        }
        foreach ($result['shifts'] as $shift) {
            $result['summary']['volumeBoards'] += $shift['volumeBoards'];
            $result['summary']['downtime']->add(BaseEntity::stringToInterval($shift['downtime']));
        }

        $result['summary']['volumeBoards'] = round($result['summary']['volumeBoards'], BaseEntity::PRECISION_FOR_FLOAT);
        $result['summary']['downtime'] = BaseEntity::intervalToString(date_diff(new DateTime('00:00'), $result['summary']['downtime']));
        return $this->json($result);
    }

    /**
     * @Route("/total/{duration}", requirements={"duration"="today|currentShift|mountly|weekly"}, name="totalTimeDowntime")
     */
    public function getTotalTimeDowntime(DowntimeRepository $downtimeRepository, string $duration, ShiftRepository $shiftRepository)
    {   
        $period = $this->getPeriodForDuration($duration, $shiftRepository);
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
