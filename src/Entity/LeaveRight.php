<?php

namespace App\Entity;

use App\Repository\LeaveRightRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LeaveRightRepository::class)]
class LeaveRight
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'float',nullable: true)]
    private $balance;

    #[ORM\Column(type: 'string', length: 255)]
    private $status;

    #[ORM\Column(type: 'float')]
    private $unit;

    #[ORM\Column(type: 'date')]
    private $startValidityDate;

    #[ORM\Column(type: 'date')]
    private $endValidityDate;

    #[ORM\ManyToOne(targetEntity: Employe::class)]
    private $employe;

    #[ORM\ManyToOne(targetEntity: LeaveType::class)]
    private $leaveType;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBalance(): ?float
    {
        return $this->balance;
    }

    public function setBalance(float $balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getUnit(): ?float
    {
        return $this->unit;
    }

    public function setUnit(float $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    public function getStartValidityDate(): ?\DateTimeInterface
    {
        return $this->startValidityDate;
    }

    public function setStartValidityDate(\DateTimeInterface $startValidityDate): self
    {
        $this->startValidityDate = $startValidityDate;

        return $this;
    }

    public function getEndValidityDate(): ?\DateTimeInterface
    {
        return $this->endValidityDate;
    }

    public function setEndValidityDate(\DateTimeInterface $endValidityDate): self
    {
        $this->endValidityDate = $endValidityDate;

        return $this;
    }

    public function getEmploye(): ?Employe
    {
        return $this->employe;
    }

    public function setEmploye(?Employe $employe): self
    {
        $this->employe = $employe;

        return $this;
    }

    public function getLeaveType(): ?LeaveType
    {
        return $this->leaveType;
    }

    public function setLeaveType(?LeaveType $leaveType): self
    {
        $this->leaveType = $leaveType;

        return $this;
    }
}
