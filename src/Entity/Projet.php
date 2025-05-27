<?php

namespace App\Entity;

use App\Repository\ProjetRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: ProjetRepository::class)]
#[Broadcast]
class Projet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $projet_id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nomProjet = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $descriptionProjet = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $dateDebut = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $dateFin = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $satut = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProjetId(): ?int
    {
        return $this->projet_id;
    }

    public function setProjetId(int $projet_id): static
    {
        $this->projet_id = $projet_id;

        return $this;
    }

    public function getNomProjet(): ?string
    {
        return $this->nomProjet;
    }

    public function setNomProjet(?string $nomProjet): static
    {
        $this->nomProjet = $nomProjet;

        return $this;
    }

    public function getDescriptionProjet(): ?string
    {
        return $this->descriptionProjet;
    }

    public function setDescriptionProjet(?string $descriptionProjet): static
    {
        $this->descriptionProjet = $descriptionProjet;

        return $this;
    }

    public function getDateDebut(): ?\DateTime
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTime $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTime
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTime $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getSatut(): ?string
    {
        return $this->satut;
    }

    public function setSatut(?string $satut): static
    {
        $this->satut = $satut;

        return $this;
    }
}
