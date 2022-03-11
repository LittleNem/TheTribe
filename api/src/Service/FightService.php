<?php

namespace App\Service;

use App\Entity\Character;
use App\Entity\Fight;
use App\Entity\History;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\ArrayShape;

class FightService
{
    private array $fightHistory = [];
    private string $currentHistory;
    private int $currentRound = 1;
    private Fight $currentFight;
    private EntityManagerInterface $manager;
    private History $history;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }


    #[ArrayShape(['winner' => "\App\Entity\Character", 'history' => "array"])]
    public function startFight(Character $character, Character $opponent, Fight $fight)
    {
        $this->currentFight = $fight;
        $this->currentHistory = '';

        $this->launchRound($character, $opponent);
        if ($character->getHealth() != 0 && $opponent->getHealth() != 0) {
            $this->currentRound++;
            $this->startFight($character, $opponent, $this->currentFight);
        }

        $this->manager->persist($this->currentFight);
        return [
            'winner' => $character->getHealth() == 0 ? $opponent : $character,
            'history' => $this->fightHistory
        ];
    }

    private function launchRound(Character $character, Character $opponent)
    {
        list($history, $opponent) = $this->extracted($character, $opponent);
        if ($opponent->getHealth() > 0) {
            list($history, $opponent) = $this->extracted($opponent, $character);
        }

        $this->fightHistory[] = $this->currentHistory;
        /**
         *  REMONTER DANS LA DOC, SI LES 2 DEFENSES SONT > AUX ATTAQUES ADVERSES, BOUCLE INFINIE
         */
    }

    private function getTotalHealthPointToSubstract(Character $character, Character $characterAttacked)
    {
        $this->history->setDiceValue($this->launchDice($character));
        $defValue = $characterAttacked->getDefense();
        $diffBtwDiceValueNDef = $this->history->getDiceValue() - $defValue;
        $originalValue =
            'diceValue : ' . $this->history->getDiceValue() . PHP_EOL .
            'opponentHealthVal : ' .  $character->getHealth() . PHP_EOL .
            'diffBtwDiceValueNDef : ' . $diffBtwDiceValueNDef . PHP_EOL .
            'currentCharacterMagik : ' . $character->getMagik() . PHP_EOL .
            'currentCharacterAttack : ' . $character->getAttack();
        $this->currentHistory .= print_r($originalValue, true) . PHP_EOL;

        if (max(0, $diffBtwDiceValueNDef)) {
            if ($diffBtwDiceValueNDef === $character->getMagik()) {
                $damageApply = $diffBtwDiceValueNDef + $character->getMagik();
                $this->currentHistory .= 'The difference between dice value n def opponent equals to magik skill => ' .
                    $diffBtwDiceValueNDef . ' + ' . $character->getMagik() . ' = ' . $damageApply . ' apply' . PHP_EOL;
                return $damageApply;
            }
            $this->currentHistory .= 'Value dice only apply => ' . $diffBtwDiceValueNDef . ' apply' . PHP_EOL;
            return $diffBtwDiceValueNDef;
        }
        $this->currentHistory .= 'Attack failed, 0 damage apply' . PHP_EOL;
        return 0;
    }

    /**
     * @throws \Exception
     */
    private function launchDice(Character $character) {
        return match ($character->getAttack()) {
            null, 0, => 0,
            1 => $character->getAttack(),
            default => random_int(1, (int)$character->getAttack()),
        };
    }

    private function substractHealthPoint(Character $characterAttacked, int $healthPointToSubstract)
    {
        $newHealthPoint = max(
            0,
            (int)$characterAttacked->getHealth() - $healthPointToSubstract
        );
        $this->currentHistory .= $characterAttacked->getName() . ' had ' . $characterAttacked->getHealth()
            . ' hit points, now, ' . $newHealthPoint . PHP_EOL;
        $this->opponentHealtStatus = $newHealthPoint;
        $characterAttacked->setHealth($newHealthPoint);

        return $newHealthPoint;
    }

    /**
     * @param Character $character
     * @param Character $opponent
     * @return array
     */
    private function extracted(Character $character, Character $opponent): array
    {
        $this->history = new History();
        $this->history->setFight($this->currentFight);
        $this->history->setCharacter($character);
        $this->history->setRound($this->currentRound);

        $this->currentHistory .= '=== ' . $character->getName() . ' turn ===' . PHP_EOL;
        $this->history->setDamage($this->getTotalHealthPointToSubstract($character, $opponent));
        $this->currentHistory .= '--------- FIGHT ----------' . PHP_EOL;
        $this->history->setOpponentHealthValue(
            $this->substractHealthPoint($opponent, $this->history->getDamage())
        );
        $this->manager->persist($this->history);
        $this->currentFight->addHistory($this->history);

        return array($this->history, $opponent);
    }
}