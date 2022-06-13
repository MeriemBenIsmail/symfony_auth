<?php

namespace App\Entity;

use App\Repository\AdminRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdminRepository::class)]
class Admin extends User
{

    #[ORM\Column(type: 'boolean')]
    private $isSuper;

    #[ORM\ManyToMany(targetEntity: Group::class, mappedBy: 'admins')]
    private $groups;

    #[ORM\ManyToMany(targetEntity: AdminRole::class, mappedBy: 'admins')]
    private $adminRoles;


    public function __construct()
    {
        $this->groups = new ArrayCollection();
        $this->adminRoles = new ArrayCollection();
    }


    public function isIsSuper(): ?bool
    {
        return $this->isSuper;
    }

    public function setIsSuper(bool $isSuper): self
    {
        $this->isSuper = $isSuper;

        return $this;
    }

    /**
     * @return Collection<int, Group>
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(Group $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups[] = $group;
            $group->addAdmin($this);
        }

        return $this;
    }

    public function removeGroup(Group $group): self
    {
        if ($this->groups->removeElement($group)) {
            $group->removeAdmin($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, AdminRole>
     */
    public function getAdminRoles(): Collection
    {
        return $this->adminRoles;
    }

    public function addAdminRole(AdminRole $adminRole): self
    {
        if (!$this->adminRoles->contains($adminRole)) {
            $this->adminRoles[] = $adminRole;
            $adminRole->addAdmin($this);
        }

        return $this;
    }

    public function removeAdminRole(AdminRole $adminRole): self
    {
        if ($this->adminRoles->removeElement($adminRole)) {
            $adminRole->removeAdmin($this);
        }

        return $this;
    }

}
