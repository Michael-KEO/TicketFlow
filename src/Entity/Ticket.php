<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
#[Broadcast]
class Ticket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $ticket_id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $titreTicket = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $typeTicket = null;

    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $descriptionTicket = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prioriteTicket = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $statutTicket = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $dateCreation = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $dateEcheance = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $dateResolution = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTicketId(): ?int
    {
        return $this->ticket_id;
    }

    public function setTicketId(int $ticket_id): static
    {
        $this->ticket_id = $ticket_id;

        return $this;
    }

    public function getTitreTicket(): ?string
    {
        return $this->titreTicket;
    }

    public function setTitreTicket(?string $titreTicket): static
    {
        $this->titreTicket = $titreTicket;

        return $this;
    }

    public function getTypeTicket(): ?string
    {
        return $this->typeTicket;
    }

    public function setTypeTicket(?string $typeTicket): static
    {
        $this->typeTicket = $typeTicket;

        return $this;
    }

    public function getDescriptionTicket(): ?string
    {
        return $this->descriptionTicket;
    }

    public function setDescriptionTicket(?string $descriptionTicket): static
    {
        $this->descriptionTicket = $descriptionTicket;

        return $this;
    }

    public function getPrioriteTicket(): ?string
    {
        return $this->prioriteTicket;
    }

    public function setPrioriteTicket(?string $prioriteTicket): static
    {
        $this->prioriteTicket = $prioriteTicket;

        return $this;
    }

    public function getStatutTicket(): ?string
    {
        return $this->statutTicket;
    }

    public function setStatutTicket(?string $statutTicket): static
    {
        $this->statutTicket = $statutTicket;

        return $this;
    }

    public function getDateCreation(): ?\DateTime
    {
        return $this->dateCreation;
    }

    public function setDateCreation(?\DateTime $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getDateEcheance(): ?\DateTime
    {
        return $this->dateEcheance;
    }

    public function setDateEcheance(?\DateTime $dateEcheance): static
    {
        $this->dateEcheance = $dateEcheance;

        return $this;
    }

    public function getDateResolution(): ?\DateTime
    {
        return $this->dateResolution;
    }

    public function setDateResolution(?\DateTime $dateResolution): static
    {
        $this->dateResolution = $dateResolution;

        return $this;
    }
}
