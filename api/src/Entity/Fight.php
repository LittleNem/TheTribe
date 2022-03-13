<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Controller\FightController;
use App\Repository\FightRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: FightRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    itemOperations: [
        'GET',
        'PUT',
        'DELETE',
        'PATCH',
        'launchFight' => [
            'method' => 'POST',
            'path' => '/fights/launch',
            'controller' => FightController::class,
            'read' => false,
            'openapi_context' => [
                'summary' => 'Launch fight with the opponent',
                'description' => 'To launch fight with the opponent',
            ],
        ],
    ],
    normalizationContext: ['groups' => ['fights_read'], 'enable_max_depth' => true]
)]
class Fight
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['fights_read', 'character_read'])]
    private $id;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['fights_read', 'character_read'])]
    private $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $updatedAt;

    #[ORM\ManyToMany(targetEntity: Character::class, mappedBy: 'fights')]
    #[Groups('fights_read')]
    #[ApiSubresource(maxDepth: 1)]
    private $characters;

    #[ORM\ManyToOne(targetEntity: Character::class, inversedBy: 'fightsWon')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['fights_read'])]
    #[MaxDepth(1)]

    private $winner;

    #[ORM\OneToMany(mappedBy: 'fight', targetEntity: History::class, orphanRemoval: true)]
    #[Groups(['fights_read'])]
    private $histories;

    public function __construct()
    {
        $this->characters = new ArrayCollection();
        $this->histories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue()
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    /**
     * @return Collection<int, Character>
     */
    public function getCharacters(): Collection
    {
        return $this->characters;
    }

    public function addCharacter(Character $character): self
    {
        if (!$this->characters->contains($character)) {
            $this->characters[] = $character;
            $character->addFight($this);
        }

        return $this;
    }

    public function removeCharacter(Character $character): self
    {
        if ($this->characters->removeElement($character)) {
            $character->removeFight($this);
        }

        return $this;
    }

    public function getWinner(): ?Character
    {
        return $this->winner;
    }

    public function setWinner(?Character $winner): self
    {
        $this->winner = $winner;

        return $this;
    }

    /**
     * @return Collection<int, History>
     */
    public function getHistories(): Collection
    {
        return $this->histories;
    }

    public function addHistory(History $history): self
    {
        if (!$this->histories->contains($history)) {
            $this->histories[] = $history;
            $history->setFight($this);
        }

        return $this;
    }

    public function removeHistory(History $history): self
    {
        if ($this->histories->removeElement($history)) {
            // set the owning side to null (unless already changed)
            if ($history->getFight() === $this) {
                $history->setFight(null);
            }
        }

        return $this;
    }
}
