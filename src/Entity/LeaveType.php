<?php

namespace App\Entity;

use App\Repository\LeaveTypeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LeaveTypeRepository::class)]
class LeaveType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $description;

    #[ORM\Column(type: 'boolean')]
    private $annual;

    #[ORM\Column(type: 'float')]
    private $validityDuration;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function isAnnual(): ?bool
    {
        return $this->annual;
    }

    public function setAnnual(bool $annual): self
    {
        $this->annual = $annual;

        return $this;
    }

    public function getValidityDuration(): ?float
    {
        return $this->validityDuration;
    }

    public function setValidityDuration(float $validityDuration): self
    {
        $this->validityDuration = $validityDuration;

        return $this;
    }
}
