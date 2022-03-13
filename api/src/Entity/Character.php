<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Controller\CharacterFightController;
use App\Controller\CharacterOpponentController;
use App\Repository\CharacterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CharacterRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    itemOperations: [
        'GET',
        'PUT',
        'DELETE',
        'PATCH',
        'opponent' => [
            'method' => 'GET',
            'path' => '/characters/{id}/opponent',
            'controller' => CharacterOpponentController::class,
            'openapi_context' => [
                'summary' => 'Choose an opponent to current character',
                'description' => 'Get the better opponent for the current character according to his level',
            ],
        ],
        'fights' => [
            'method' => 'GET',
            'path' => '/characters/{id}/fights',
            'controller' => CharacterFightController::class,
            'openapi_context' => [
                'summary' => 'Get all fights',
                'description' => 'list all fights with this character',
            ],
        ],
    ],
    denormalizationContext: ['groups' => ['character_write']],
    normalizationContext: ['groups' => 'character_read']
)]
class Character
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['character_read'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['fights_read', 'character_read', 'character_write'])]
    private $name;

    #[ORM\Column(type: 'integer', options: ['default' => 1])]
    #[Groups(['character_read'])]
    private $rank = 1;

    #[ORM\Column(type: 'integer', options: ['default' => 12])]
    #[Groups(['character_read'])]
    private $skillPoints = 12;

    #[ORM\Column(type: 'integer', options: ['default' => 10])]
    #[Groups(['character_read'])]
    private $health = 10;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    #[Groups(['character_read'])]
    private $attack = 0;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    #[Groups(['character_read'])]
    private $defense = 0;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    #[Groups(['character_read'])]
    private $magik = 0;

    #[ORM\Column(type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    #[Groups(['character_read'])]
    private $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true, options: ['default' => 'CURRENT_TIMESTAMP'])]
    #[Groups(['character_read'])]
    private $updatedAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    #[Groups(['character_read'])]
    private $delay;

    #[ORM\ManyToMany(targetEntity: Fight::class, inversedBy: 'characters')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups('character_read')]
    #[ApiSubresource(maxDepth: 1)]
    private $fights;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'characters')]
    #[ORM\JoinColumn(nullable: true)]
    private $ownedBy;

    #[ORM\OneToMany(mappedBy: 'winner', targetEntity: Fight::class, orphanRemoval: true)]
    private $fightsWon;

    #[ORM\OneToMany(mappedBy: 'character', targetEntity: History::class, orphanRemoval: true)]
    private $histories;

    public function __construct()
    {
        $this->fights = new ArrayCollection();
        $this->fightsWon = new ArrayCollection();
        $this->histories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getRank(): ?int
    {
        return $this->rank;
    }

    public function setRank(int $rank): self
    {
        $this->rank = $rank;

        return $this;
    }

    public function getSkillPoints(): ?int
    {
        return $this->skillPoints;
    }

    public function setSkillPoints(int $skillPoints): self
    {
        $this->skillPoints = $skillPoints;

        return $this;
    }

    public function getHealth(): ?int
    {
        return $this->health;
    }

    public function setHealth(int $health): self
    {
        $this->health = $health;

        return $this;
    }

    public function getAttack(): ?int
    {
        return $this->attack;
    }

    public function setAttack(int $attack): self
    {
        $this->attack = $attack;

        return $this;
    }

    public function getDefense(): ?int
    {
        return $this->defense;
    }

    public function setDefense(int $defense): self
    {
        $this->defense = $defense;

        return $this;
    }

    public function getMagik(): ?int
    {
        return $this->magik;
    }

    public function setMagik(int $magik): self
    {
        $this->magik = $magik;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getDelay(): ?\DateTimeImmutable
    {
        return $this->delay;
    }

    public function setDelay(?\DateTimeImmutable $delay): self
    {
        $this->delay = $delay;

        return $this;
    }

    /**
     * @return Collection<int, Fight>
     */
    public function getFights(): Collection
    {
        return $this->fights;
    }

    public function addFight(Fight $fight): self
    {
        if (!$this->fights->contains($fight)) {
            $this->fights[] = $fight;
        }

        return $this;
    }

    public function removeFight(Fight $fight): self
    {
        $this->fights->removeElement($fight);

        return $this;
    }

    public function getOwnedBy(): ?User
    {
        return $this->ownedBy;
    }

    public function setOwnedBy(?User $ownedBy): self
    {
        $this->ownedBy = $ownedBy;

        return $this;
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
     * @return Collection<int, Fight>
     */
    public function getFightsWon(): Collection
    {
        return $this->fightsWon;
    }

    public function addFightsWon(Fight $fightsWon): self
    {
        if (!$this->fightsWon->contains($fightsWon)) {
            $this->fightsWon[] = $fightsWon;
            $fightsWon->setWinner($this);
        }

        return $this;
    }

    public function removeFightsWon(Fight $fightsWon): self
    {
        if ($this->fightsWon->removeElement($fightsWon)) {
            // set the owning side to null (unless already changed)
            if ($fightsWon->getWinner() === $this) {
                $fightsWon->setWinner(null);
            }
        }

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
            $history->setCharacter($this);
        }

        return $this;
    }

    public function removeHistory(History $history): self
    {
        if ($this->histories->removeElement($history)) {
            // set the owning side to null (unless already changed)
            if ($history->getCharacter() === $this) {
                $history->setCharacter(null);
            }
        }

        return $this;
    }
}
