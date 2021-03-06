<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: "discr", type: "string")]
#[ORM\DiscriminatorMap(["user" => User::class, "employe" => Employe::class])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: 'integer')]
    private $id;
    #[Assert\Email(message: "The email '{{ value }}' is not a valid email.")]
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    protected $email;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $super;

    #[ORM\Column(type: 'json')]
    private $roles = [];
    #[SecurityAssert\UserPassword(message: "Wrong value for your current password")]
    #[ORM\Column(type: 'string')]
    protected $password;


    #[ORM\ManyToMany(targetEntity: Group::class, mappedBy: 'users')]
    private $groups;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
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

    public function getUsername(): string
    {
        return (string)$this->email;
    }

    public function isSuper(): ?bool
    {
        return $this->super;
    }

    public function setSuper(bool $super): self
    {
        $this->super = $super;

        return $this;
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

        $groups = $this->getGroups();
        $rolesUnion = $this->roles;
        foreach ($groups as $group) {
            if ($group) {
                if ($group->getRoles()) {
                    $rolesUnion = array_merge($rolesUnion, $group->getRoles());
                }
            }
        }
        if ($rolesUnion) {

            return array_unique($rolesUnion);
        }
        return [];
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function uniteRoles(array $roles): self
    {
        foreach ($roles as $role) {

            $this->roles[] = $role;
        }
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
     * @return Collection<int, Group>
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function emptyGroups(): self
    {
        foreach ($this->groups as $group) {
            $this->removeGroup($group);
        }
        return $this;

    }


    public function addGroup(Group $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups[] = $group;
            $group->addUser($this);

        }

        return $this;
    }

    public function removeGroup(Group $group): self
    {
        if ($this->groups->removeElement($group)) {
            $group->removeUser($this);
        }

        return $this;
    }
}
