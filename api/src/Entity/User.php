<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\UserController;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    normalizationContext: ["groups" => "users_read"]
)]
#[ApiFilter(SearchFilter::class, properties: ['email' => 'exact'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["users_read"])]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Groups(["users_read"])]
    private $email;

    #[ORM\Column(type: 'json')]
    #[Groups(["users_read"])]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'datetime_immutable', options: ["default" => "CURRENT_TIMESTAMP"])]
    private $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true, options: ["default" => "CURRENT_TIMESTAMP"])]
    private $updatedAt;

    #[ORM\OneToMany(mappedBy: 'ownedBy', targetEntity: Character::class, orphanRemoval: true)]
    #[Groups(["users_read"])]
    private $characters;

    public function __construct()
    {
        $this->characters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->getEmail();
    }

    public function setUsername(string $email): self
    {
        return $this->setEmail($email);
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
            $character->setOwnedBy($this);
        }

        return $this;
    }

    public function removeCharacter(Character $character): self
    {
        if ($this->characters->removeElement($character)) {
            // set the owning side to null (unless already changed)
            if ($character->getOwnedBy() === $this) {
                $character->setOwnedBy(null);
            }
        }

        return $this;
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
}
