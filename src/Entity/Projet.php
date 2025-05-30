<?php

namespace App\Entity;

use App\Repository\ProjetRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Client;
use App\Entity\Ticket;

#[ORM\Entity(repositoryClass: ProjetRepository::class)]
#[Broadcast]
// Entité représentant un projet dans le système
class Projet
{
    // Identifiant unique de la table (clé primaire)
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Identifiant métier du projet (différent de l'id technique)
    #[ORM\Column]
    private ?int $projet_id = null;

    // Nom du projet (optionnel)
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nomProjet = null;

    // Description du projet (optionnelle)
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $descriptionProjet = null;

    // Date de début du projet (optionnelle)
    #[ORM\Column(nullable: true)]
    private ?\DateTime $dateDebut = null;

    // Date de fin du projet (optionnelle)
    #[ORM\Column(nullable: true)]
    private ?\DateTime $dateFin = null;

    // Statut du projet (optionnel)
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $satut = null;

    // Client associé à ce projet (relation obligatoire)
    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: "projets")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    // Liste des tickets associés à ce projet
    #[ORM\OneToMany(mappedBy: "projet", targetEntity: Ticket::class)]
    private Collection $tickets;

    // Constructeur : initialise la collection de tickets
    public function __construct()
    {
        $this->tickets = new ArrayCollection();
    }

    // Retourne l'identifiant technique du projet
    public function getId(): ?int
    {
        return $this->id;
    }

    // Retourne l'identifiant métier du projet
    public function getProjetId(): ?int
    {
        return $this->projet_id;
    }

    // Définit l'identifiant métier du projet
    public function setProjetId(int $projet_id): static
    {
        $this->projet_id = $projet_id;
        return $this;
    }

    // Retourne le nom du projet
    public function getNomProjet(): ?string
    {
        return $this->nomProjet;
    }

    // Définit le nom du projet
    public function setNomProjet(?string $nomProjet): static
    {
        $this->nomProjet = $nomProjet;
        return $this;
    }

    // Retourne la description du projet
    public function getDescriptionProjet(): ?string
    {
        return $this->descriptionProjet;
    }

    // Définit la description du projet
    public function setDescriptionProjet(?string $descriptionProjet): static
    {
        $this->descriptionProjet = $descriptionProjet;
        return $this;
    }

    // Retourne la date de début du projet
    public function getDateDebut(): ?\DateTime
    {
        return $this->dateDebut;
    }

    // Définit la date de début du projet
    public function setDateDebut(?\DateTime $dateDebut): static
    {
        $this->dateDebut = $dateDebut;
        return $this;
    }

    // Retourne la date de fin du projet
    public function getDateFin(): ?\DateTime
    {
        return $this->dateFin;
    }

    // Définit la date de fin du projet
    public function setDateFin(?\DateTime $dateFin): static
    {
        $this->dateFin = $dateFin;
        return $this;
    }

    // Retourne le statut du projet
    public function getSatut(): ?string
    {
        return $this->satut;
    }

    // Définit le statut du projet
    public function setSatut(?string $satut): static
    {
        $this->satut = $satut;
        return $this;
    }

    // Retourne le client associé à ce projet
    public function getClient(): ?Client
    {
        return $this->client;
    }

    // Définit le client associé à ce projet
    public function setClient(?Client $client): static
    {
        $this->client = $client;
        return $this;
    }

    // Retourne la collection des tickets associés à ce projet
    public function getTickets(): Collection
    {
        return $this->tickets;
    }
}
