<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?Date $date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pruduct = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?bool $is_status = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?User $farmer = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $validation_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $cancel_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getDate(): ?Date
    {
        return $this->date;
    }

    public function setDate(?Date $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getPruduct(): ?string
    {
        return $this->pruduct;
    }

    public function setPruduct(?string $pruduct): static
    {
        $this->pruduct = $pruduct;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->is_status;
    }

    public function setStatus(?bool $is_status): static
    {
        $this->is_status = $is_status;

        return $this;
    }

    public function getFarmer(): ?User
    {
        return $this->farmer;
    }

    public function setFarmer(?User $farmer): static
    {
        $this->farmer = $farmer;

        return $this;
    }

    public function getValidationAt(): ?\DateTimeImmutable
    {
        return $this->validation_at;
    }

    public function setValidationAt(?\DateTimeImmutable $validation_at): static
    {
        $this->validation_at = $validation_at;

        return $this;
    }

    public function getCancelAt(): ?\DateTimeImmutable
    {
        return $this->cancel_at;
    }

    public function setCancelAt(?\DateTimeImmutable $cancel_at): static
    {
        $this->cancel_at = $cancel_at;

        return $this;
    }
}
