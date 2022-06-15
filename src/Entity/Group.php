<?php

namespace App\Entity;

use App\Repository\GroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(name: '`group`')]
class Group
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;


    #[ORM\Column(type: 'string')]
    private $name;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'groups')]
    private $users;

    #[ORM\ManyToMany(targetEntity: UserRole::class)]
    private $groupRoles;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->groupRoles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, User>
     */

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function emptyUsers(): self
    {
        foreach ($this->users as $user) {
            $this->removeUser($user);
        }
        return $this;

    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }

    /**
     * @return Collection<int, UserRole>
     */
    public function getGroupRoles(): Collection
    {
        return $this->groupRoles;
    }

    public function emptyGroupRoles(): self
    {
        foreach ($this->groupRoles as $groupRole) {
            $this->removeGroupRole($groupRole);
        }
        return $this;

    }

    public function addGroupRole(UserRole $groupRole): self
    {
        if (!$this->groupRoles->contains($groupRole)) {
            $this->groupRoles[] = $groupRole;
        }

        return $this;
    }

    public function removeGroupRole(UserRole $groupRole): self
    {
        $this->groupRoles->removeElement($groupRole);

        return $this;
    }
}
