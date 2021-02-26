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
use DoctrineExtensions\Query\Mysql\Date;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route("api/infocard", name:"info_card_")]
class InfoCardController extends AbstractController
{

    private DowntimeRepository $downtimeRepository;
    private ShiftRepository $shiftRepository;
    private TimberRepository $timberRepository;

    public function __construct(ShiftRepository $shiftRepository, TimberRepository $timberRepository, DowntimeRepository $downtimeRepository)
    {
        $this->downtimeRepository = $downtimeRepository;
        $this->shiftRepository = $shiftRepository;
        $this->timberRepository = $timberRepository;
    }

    #[Route("/currentShift", name:"currentShift")]
    public function getCurrentShift()
    {
        $currentShift = $this->shiftRepository->getCurrentShift();
        if (!$currentShift)
            return $this->json(['value' => 'Не начата', 'color' => 'error'], 204);

        return $this->json([
            'value' => $currentShift->getPeople()->getFio(),
            'subtitle' => 'Смена № ' . $currentShift->getNumber(),
            'color' => 'info'
        ]);
    }
    
    private function getPeriodForDuration(string $duration): ?DatePeriod
    {
        switch ($duration) {
            case 'today':
                $period = BaseEntity::getPeriodForDay();
                break;

            case 'weekly':
                $period = BaseEntity::getPeriodForDay();
                $lastMonday = new DateTime('last monday ' . $period->end->format(BaseEntity::DATE_FORMAT_DB));
                $period = new DatePeriod($lastMonday, $period->getDateInterval(), $period->end);
                break;

            case 'mountly':
                $period = BaseEntity::getPeriodForDay();
                $start = $period->getStartDate();
                $start->setDate((int)$start->format('Y'), (int)$start->format('n'), 1);
                $period = new DatePeriod($start, $period->getDateInterval(), $period->getEndDate());
                break;

            case 'currentShift':
                $currentShift = $this->shiftRepository->getCurrentShift();
                if (!$currentShift)
                    return null;
                $period = $currentShift->getPeriod();
                break;
        }

        return $period;
    }

    #[Route("/volumeBoard/{duration}", requirements:[ "duration" => "today|currentShift|mountly|weekly"] , name:"volumeBoard")]
    public function getVolumeBoard(string $duration)
    {
        $period = $this->getPeriodForDuration($duration, $this->shiftRepository);
        if (!$period instanceof DatePeriod)
            return $this->json(['value' => '0', 'color' => 'error'], 204);

        $volumeBoard = number_format($this->timberRepository->getVolumeBoardsByPeriod($period), BaseEntity::PRECISION_FOR_FLOAT, '.', ' ') . ' м3';
        return $this->json([
            'value' => $volumeBoard,
            'color' => 'info'
        ]);
    }    
    
    #[Route("/countBoard/{duration}", requirements:[ "duration" => "today|currentShift|mountly|weekly"] , name:"countBoard")]
    public function getCountBoard(string $duration)
    {
        $period = $this->getPeriodForDuration($duration, $this->shiftRepository);
        if (!$period instanceof DatePeriod)
            return $this->json(['value' => '0', 'color' => 'error'], 204);

        $countBoard = $this->timberRepository->getCountBoardsByPeriod($period) . ' шт';
        return $this->json([
            'value' => $countBoard,
            'color' => 'info'
        ]);
    }

    #[Route("/countTimber/{duration}", requirements:["duration"=>"today|currentShift|mountly|weekly"], name:"countTimber")]
    public function getCountTimber(string $duration)
    {
        $period = $this->getPeriodForDuration($duration, $this->shiftRepository);
        if (!$period instanceof DatePeriod)
            return $this->json(['value' => '0', 'color' => 'error'], 204);

        $countTimber = $this->timberRepository->getCountTimberByPeriod($period) . ' шт.';
        return $this->json([
            'value' => $countTimber,
            'color' => 'info'
        ]);
    }

    #[Route("/volumeTimber/{duration}", requirements:["duration"=>"today|currentShift|mountly|weekly"], name:"volumeTimber")]
    public function getVolumeTimber(string $duration)
    {
        $period = $this->getPeriodForDuration($duration, $this->shiftRepository);
        if (!$period instanceof DatePeriod)
            return $this->json(['value' => '0', 'color' => 'error'], 204);

        $volumeTimber = number_format($this->timberRepository->getVolumeTimberByPeriod($period), BaseEntity::PRECISION_FOR_FLOAT, '.', ' ') . ' м3';
        return $this->json([
            'value' => $volumeTimber,
            'color' => 'info'
        ]);
    }

    #[Route("/lastDowntime", name:"lastDowntime")]
    public function getLastDowntime()
    {
        $lastDowntime = $this->downtimeRepository->getLastDowntime();
        if (!$lastDowntime)
            return $this->json(['value' => '', 'color' => 'error'], 204);

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

    #[Route("/summaryDay/{start}...{end}", name:"summaryDay")]
    public function getSummaryDay(string $start, string $end)
    {
        $startDate = new DateTime($start);
        $endDate = new DateTime($end);
        $period = new DatePeriod($startDate, new DateInterval('P1D'), $endDate);

        $shifts = $this->shiftRepository->findByPeriod($period);
        if (!$shifts)
            return $this->json('Нет смен за заданный день', 404);

        $result['summary'] = ['volumeBoards' => 0, 'downtime' => new DateTime('00:00')];
        foreach ($shifts as $key => $shift) {
            $result['shifts'][$key]['name'] = 'Смена №' . $shift->getNumber();
            $result['shifts'][$key]['idOperator'] = $shift->getPeople()->getId();
            $result['shifts'][$key]['fioOperator'] = $shift->getPeople()->getFio();
            $result['shifts'][$key]['start'] = $shift->getStart();
            $result['shifts'][$key]['end'] = $shift->getStop() ? $shift->getStop()->format(BaseEntity::DATE_FORMAT_DB) : date(BaseEntity::DATE_FORMAT_DB); 
            $result['shifts'][$key]['volumeBoards'] = round($this->timberRepository->getVolumeBoardsByPeriod($shift->getPeriod()), BaseEntity::PRECISION_FOR_FLOAT);
            $result['shifts'][$key]['downtime'] = $this->downtimeRepository->getTotalDowntimeByPeriod($shift->getPeriod());
        }
        foreach ($result['shifts'] as $shift) {
            $result['summary']['volumeBoards'] += $shift['volumeBoards'];
            $result['summary']['downtime']->add(BaseEntity::stringToInterval($shift['downtime']));
        }

        $result['summary']['volumeBoards'] = round($result['summary']['volumeBoards'], BaseEntity::PRECISION_FOR_FLOAT);
        $result['summary']['downtime'] = BaseEntity::intervalToString(date_diff(new DateTime('00:00'), $result['summary']['downtime']));
        return $this->json($result);
    }

    #[Route("/totalDowntime/{duration}", requirements:["duration"=>"today|currentShift|mountly|weekly"], name:"totalTimeDowntime")]
    public function getTotalTimeDowntime(string $duration)
    {
        $period = $this->getPeriodForDuration($duration, $this->shiftRepository);
        if (!$period instanceof DatePeriod)
            return $this->json(['value' => '0', 'color' => 'error'], 204);

        $durationTime = $this->downtimeRepository->getTotalDowntimeByPeriod($period);

        if (!$durationTime)
            return $this->json(['value' => '', 'color' => 'error'], 204);

        return $this->json([
            'value' => $durationTime ?? '',
            // 'subtitle' => $duration . '. C ' . $startTime->format(BaseEntity::TIME_FOR_FRONT . '(d.m)') . ' по ' . $endTime->format(BaseEntity::DATETIME_FOR_FRONT . '(d.m)'),
            'color' => 'primary',
        ]);
    }
}
