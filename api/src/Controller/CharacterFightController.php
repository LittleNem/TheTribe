<?php

namespace App\Controller;

use App\Entity\Character;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CharacterFightController extends AbstractController
{
    public function __invoke(Character $data): array
    {
        return [$data->getFights()];
    }
}