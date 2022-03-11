<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\HistoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;


#[ORM\Entity(repositoryClass: HistoryRepository::class)]
#[ApiResource]
class History
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    #[Groups(["fights_read", "character_read"])]
    private $round;

    #[ORM\Column(type: 'integer')]
    #[Groups(["fights_read", "character_read"])]
    private $diceValue;

    #[ORM\Column(type: 'integer')]
    #[Groups(["fights_read", "character_read"])]
    private $damage;

    #[ORM\Column(type: 'integer')]
    #[Groups(["fights_read", "character_read"])]
    private $opponentHealthValue;


    #[ORM\ManyToOne(targetEntity: Fight::class, inversedBy: 'histories')]
    #[ORM\JoinColumn(nullable: false)]
    private $fight;

    #[ORM\ManyToOne(targetEntity: Character::class, inversedBy: 'histories')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["fights_read", "character_read"])]
    #[ApiSubresource(maxDepth: 1)]
    private $character;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getRound(): ?int
    {
        return $this->round;
    }

    /**
     * @param int $round
     * @return $this
     */
    public function setRound(int $round): self
    {
        $this->round = $round;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getDiceValue(): ?int
    {
        return $this->diceValue;
    }

    /**
     * @param int $diceValue
     * @return $this
     */
    public function setDiceValue(int $diceValue): self
    {
        $this->diceValue = $diceValue;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getDamage(): ?int
    {
        return $this->damage;
    }

    /**
     * @param int $damage
     * @return $this
     */
    public function setDamage(int $damage): self
    {
        $this->damage = $damage;

        return $this;
    }

    /**
     * @return Fight|null
     */
    public function getFight(): ?Fight
    {
        return $this->fight;
    }

    /**
     * @param Fight|null $fight
     * @return $this
     */
    public function setFight(?Fight $fight): self
    {
        $this->fight = $fight;

        return $this;
    }

    /**
     * @return Character|null
     */
    public function getCharacter(): ?Character
    {
        return $this->character;
    }

    /**
     * @param Character|null $character
     * @return $this
     */
    public function setCharacter(?Character $character): self
    {
        $this->character = $character;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOpponentHealthValue()
    {
        return $this->opponentHealthValue;
    }

    /**
     * @param mixed $opponentHealthValue
     */
    public function setOpponentHealthValue(int $opponentHealthValue): void
    {
        $this->opponentHealthValue = $opponentHealthValue;
    }
}
