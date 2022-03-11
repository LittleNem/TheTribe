<?php

namespace App\Controller;

use App\Entity\Fight;
use App\Repository\CharacterRepository;
use App\Repository\HistoryRepository;
use App\Service\FightService;
use DateInterval;
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
        $character1 = $this->characterRepository->find($postDatas['idOpponent1']);
        $character1HealthOriginal = $character1->getHealth();
        $character2 = $this->characterRepository->find($postDatas['idOpponent2']);
        $character2HealthOriginal = $character2->getHealth();
        $fight = new Fight();
        $fight->addCharacter($character1);
        $fight->addCharacter($character2);

        $fightResult = $this->fightService->startFight($character1, $character2, $fight);

        $winner = $fightResult['winner'];
        if ($winner->getId() == $character1->getId()) {
            $character1->setHealth($character2HealthOriginal);
            $characterWinner = $character1;
            $character2->setHealth($character2HealthOriginal);
            $characterLooser = $character2;
        } else {
            $character2->setHealth($character2HealthOriginal);
            $characterWinner = $character2;
            $character1->setHealth($character1HealthOriginal);
            $characterLooser = $character1;
        }

        $fight->setWinner($characterWinner);
        $this->manager->persist($fight);

        $characterWinner->setRank($winner->getRank()+1);
        $characterWinner->setSkillPoints($winner->getSkillPoints()+1);
        $characterLooserRank = $characterLooser->getRank();
        if ($characterLooserRank > 1) {
            $characterLooser->setRank($characterLooserRank - 1);
        }

        $newDelayToPlay = new \DateTimeImmutable('now');
        $newDelayToPlay = $newDelayToPlay->add(new DateInterval('PT60M'));

        $characterLooser->setDelay($newDelayToPlay);
        $this->manager->persist($characterWinner);
        $this->manager->persist($characterLooser);
        $this->manager->flush();

        return [$fight];
    }
}