<?php

namespace App\Entity;

use App\Repository\UserRoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRoleRepository::class)]
class  UserRole
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;


    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'userRoles')]
    #[ORM\JoinColumn(onDelete:"CASCADE")]
    private $userRole;

    #[ORM\OneToMany(mappedBy: 'userRole', targetEntity: self::class)]
    private $userRoles;


    public function __construct()
    {
        $this->userRoles = new ArrayCollection();
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



    public function getUserRole(): ?self
    {
        return $this->userRole;
    }

    public function setUserRole(?self $userRole): self
    {
        $this->userRole = $userRole;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getUserRoles(): Collection
    {
        return $this->userRoles;
    }
    public function  emptyUserRoles():self
    {
        $this->userRoles=new ArrayCollection();
        return $this;

    }

    public function addUserRole(self $userRole): self
    {
        if (!$this->userRoles->contains($userRole)) {
            $this->userRoles[] = $userRole;
            $userRole->setUserRole($this);
        }

        return $this;
    }

    public function removeUserRole(self $userRole): self
    {
        if ($this->userRoles->removeElement($userRole)) {
            // set the owning side to null (unless already changed)
            if ($userRole->getUserRole() === $this) {
                $userRole->setUserRole(null);
            }
        }

        return $this;
    }


}
