<?php

namespace App\Domain\Entity;

use App\Domain\DTO\UpdateUserDTO;
use App\Domain\DTO\UserRegistrationDTO;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="Il y a déjà un compte avec cet email")
 */
class User implements UserInterface
{
    /**
     * @var \Ramsey\Uuid\UuidInterface
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $vkey = null;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $username;

    /**
     * @ORM\OneToMany(targetEntity="App\Domain\Entity\Trick", mappedBy="user", orphanRemoval=true)
     */
    private $tricks;

    /**
     * @ORM\OneToMany(targetEntity="App\Domain\Entity\Comment", mappedBy="user", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\OneToOne(targetEntity="App\Domain\Entity\Picture", mappedBy="user", cascade={"persist", "remove"})
     */
    private $picture;

    public function __construct()
    {
        $this->tricks = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getVkey(): ?string
    {
        return $this->vkey;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->vkey = null;
    }

    public function updateRole(array $roles)
    {
        $this->roles = $roles;

        return $this;
    }

    public function setVkey(string $vkey)
    {
        $this->vkey = $vkey;

        return $this;
    }

    public function hasRole(string $role)
    {
        return in_array($role, $this->roles);
    }

    public function exist(string $email)
    {
        return $email === $this->getEmail();
    }

    public function newPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    public function createFromRegistration(UserRegistrationDTO $registrationDTO): self
    {
        $this->email = $registrationDTO->email;
        $this->password = $registrationDTO->password;
        $this->username = $registrationDTO->username;
        $this->vkey = $registrationDTO->vkey;
        $this->roles = $registrationDTO->roles;
        $this->picture = $registrationDTO->picture;

        return $this;
    }

    public function updateFromDTO(UpdateUserDTO $userDTO)
    {
        $this->username = $userDTO->username;
        $this->picture = $userDTO->picture;
    }

    /**
     * @return Collection|Trick[]
     */
    public function getTricks(): Collection
    {
        return $this->tricks;
    }

    public function addTrick(Trick $trick): self
    {
        if (!$this->tricks->contains($trick)) {
            $this->tricks[] = $trick;
            $trick->setUser($this);
        }

        return $this;
    }

    public function removeTrick(Trick $trick): self
    {
        if ($this->tricks->contains($trick)) {
            $this->tricks->removeElement($trick);
            // set the owning side to null (unless already changed)
            if ($trick->getUser() === $this) {
                $trick->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addCreatedAt(Comment $comments): self
    {
        if (!$this->comments->contains($comments)) {
            $this->comments[] = $comments;
            $comments->setUser($this);
        }

        return $this;
    }

    public function removeCreatedAt(Comment $comments): self
    {
        if ($this->comments->contains(comments)) {
            $this->comments->removeElement($comments);
            // set the owning side to null (unless already changed)
            if ($comments->getUser() === $this) {
                $comments->setUser(null);
            }
        }

        return $this;
    }

    public function getPicture(): ?Picture
    {
        return $this->picture;
    }

    public function setPicture(Picture $picture): self
    {
        $this->picture = $picture;

        // set the owning side of the relation if necessary
        if ($this !== $picture->getUser()) {
            $picture->setUser($this);
        }

        return $this;
    }
}
