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


    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'childRoles')]
    private $parentRole;

    #[ORM\OneToMany(mappedBy: 'parentRole', targetEntity: self::class)]
    private $childRoles;


    public function __construct()
    {
        $this->childRoles = new ArrayCollection();
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


    public function getParentRole(): ?self
    {
        return $this->parentRole;
    }

    public function setParentRole(?self $parentRole): self
    {
        $this->parentRole = $parentRole;
        if($parentRole!=null){
            $parentRole->addChildRole($this);
        }
        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getChildRoles(): Collection
    {
        return $this->childRoles;
    }

    public function emptyChildRoles(): self
    {
        foreach ($this->childRoles as $child) {
            $this->removeChildRole($child);
        }
        return  $this;
    }

    public function addChildRole(self $childRole): self
    {
        if (!$this->childRoles->contains($childRole)) {
            $this->childRoles[] = $childRole;
            $childRole->setParentRole($this);
        }

        return $this;
    }

    public function removeChildRole(self $childRole): self
    {
        if ($this->childRoles->removeElement($childRole)) {
            // set the owning side to null (unless already changed)
            if ($childRole->getParentRole() === $this) {
                $childRole->setParentRole(null);
            }
        }

        return $this;
    }


}
