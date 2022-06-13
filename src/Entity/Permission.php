<?php

namespace App\Entity;

use App\Enum\PermissionType;
use App\Repository\PermissionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PermissionRepository::class)]
class Permission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', enumType: PermissionType::class)]
    private $type;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?PermissionType
    {
        return $this->type;
    }

    public function setType(object $type): self
    {
        $this->type = $type;

        return $this;
    }
}
