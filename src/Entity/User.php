<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'string', length: 255)]
    private $pseudo;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $lang1;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $lang2;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $lang3;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $lang4;

    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'users')]
    private $lstAmis;

    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'lstAmis')]
    private $users;

    public function __construct()
    {
        $this->lstAmis = new ArrayCollection();
        $this->users = new ArrayCollection();
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
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

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getLang1(): ?bool
    {
        return $this->lang1;
    }

    public function setLang1(?bool $lang1): self
    {
        $this->lang1 = $lang1;

        return $this;
    }

    public function getLang2(): ?bool
    {
        return $this->lang2;
    }

    public function setLang2(?bool $lang2): self
    {
        $this->lang2 = $lang2;

        return $this;
    }

    public function getLang3(): ?bool
    {
        return $this->lang3;
    }

    public function setLang3(?bool $lang3): self
    {
        $this->lang3 = $lang3;

        return $this;
    }

    public function getLang4(): ?bool
    {
        return $this->lang4;
    }

    public function setLang4(?bool $lang4): self
    {
        $this->lang4 = $lang4;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getLstAmis(): Collection
    {
        return $this->lstAmis;
    }

    public function addLstAmi(self $lstAmi): self
    {
        if (!$this->lstAmis->contains($lstAmi)) {
            $this->lstAmis[] = $lstAmi;
        }

        return $this;
    }

    public function removeLstAmi(self $lstAmi): self
    {
        $this->lstAmis->removeElement($lstAmi);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(self $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addLstAmi($this);
        }

        return $this;
    }

    public function removeUser(self $user): self
    {
        if ($this->users->removeElement($user))
        {
            $user->removeLstAmi($this);
        }

        return $this;
    }

    public function isMyFriend(self $user)
    {
        $ret =  $this->lstAmis->contains($user);
        return $ret;
    }
}
