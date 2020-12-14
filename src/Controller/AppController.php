<?php

declare(strict_types=1);

namespace App\Controller;

use App\Report\Event\ActionOperatorEventPdfReport;
use App\Report\Event\ActionOperatorEventReport;
use App\Repository\EventRepository;
use App\Repository\ShiftRepository;
use DatePeriod;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    /**
     * @Route("/", name="app")
     */
    public function index()
    {
        return $this->render('app/index.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }

    /**
     * @Route("/api/infoCard", name="currentShift")
     */
    public function getCurrentShift(ShiftRepository $shiftRepository)
    {
        $currentShift = $shiftRepository->getCurrentShift();
        dd($currentShift);
        return $this->json(['value' => 'Кравчук О.В.']);
    }
}
