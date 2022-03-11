<?php

namespace App\Controller;

use App\Entity\Character;
use App\Repository\CharacterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CharacterOpponentController extends AbstractController
{
    private CharacterRepository $characterRepository;

    public function __construct(CharacterRepository $characterRepository)
    {
        $this->characterRepository = $characterRepository;
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function __invoke(Character $data): array
    {
        return $this->characterRepository
            ->getNextFight($data);
    }
}
