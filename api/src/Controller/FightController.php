<?php

namespace App\Controller;

use App\Repository\CharacterRepository;
use App\Repository\HistoryRepository;
use App\Service\FightService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;

class FightController extends AbstractController {

    private FightService $fightService;
    private RequestStack $request;
    private CharacterRepository $characterRepository;
    private EntityManagerInterface $manager;
    private HistoryRepository $historyRepository;

    public function __construct(
        FightService $fightService,
        RequestStack $request,
        CharacterRepository $characterRepository,
        HistoryRepository $historyRepository,
        EntityManagerInterface $manager
    ) {
        $this->fightService = $fightService;
        $this->request = $request;
        $this->characterRepository = $characterRepository;
        $this->manager = $manager;
        $this->historyRepository = $historyRepository;
    }

    public function __invoke(): array
    {
        $postDatas = $this->request->getCurrentRequest()->toArray();
        return $this->fightService->initFight($postDatas);
    }
}