<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use App\Entity\Projet;
use App\Entity\User;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
#[Broadcast]
// Entité représentant un ticket dans le système de gestion
class Ticket
{
    // Identifiant unique de la table (clé primaire)
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Identifiant métier du ticket (différent de l'id technique)
    #[ORM\Column]
    private ?int $ticket_id = null;

    // Titre du ticket (optionnel)
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $titreTicket = null;

    // Type du ticket (ex : bug, feature, etc.) (optionnel)
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $typeTicket = null;

    // Description détaillée du ticket (optionnelle)
    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $descriptionTicket = null;

    // Priorité du ticket (optionnelle)
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prioriteTicket = null;

    // Statut du ticket (optionnel)
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $statutTicket = null;

    // Date de création du ticket (optionnelle)
    #[ORM\Column(nullable: true)]
    private ?\DateTime $dateCreation = null;

    // Date d'échéance du ticket (optionnelle)
    #[ORM\Column(nullable: true)]
    private ?\DateTime $dateEcheance = null;

    // Date de résolution du ticket (optionnelle)
    #[ORM\Column(nullable: true)]
    private ?\DateTime $dateResolution = null;

    // Projet auquel ce ticket est rattaché (relation obligatoire)
    #[ORM\ManyToOne(targetEntity: Projet::class, inversedBy: "tickets")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Projet $projet = null;

    // Utilisateur rapporteur du ticket (relation obligatoire)
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $rapporteur = null;

    // Utilisateur développeur assigné au ticket (optionnel)
    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $developpeur = null;

    // Retourne l'identifiant technique du ticket
    public function getId(): ?int
    {
        return $this->id;
    }

    // Retourne l'identifiant métier du ticket
    public function getTicketId(): ?int
    {
        return $this->ticket_id;
    }

    // Définit l'identifiant métier du ticket
    public function setTicketId(int $ticket_id): static
    {
        $this->ticket_id = $ticket_id;
        return $this;
    }

    // Retourne le titre du ticket
    public function getTitreTicket(): ?string
    {
        return $this->titreTicket;
    }

    // Définit le titre du ticket
    public function setTitreTicket(?string $titreTicket): static
    {
        $this->titreTicket = $titreTicket;
        return $this;
    }

    // Retourne le type du ticket
    public function getTypeTicket(): ?string
    {
        return $this->typeTicket;
    }

    // Définit le type du ticket
    public function setTypeTicket(?string $typeTicket): static
    {
        $this->typeTicket = $typeTicket;
        return $this;
    }

    // Retourne la description du ticket
    public function getDescriptionTicket(): ?string
    {
        return $this->descriptionTicket;
    }

    // Définit la description du ticket
    public function setDescriptionTicket(?string $descriptionTicket): static
    {
        $this->descriptionTicket = $descriptionTicket;
        return $this;
    }

    // Retourne la priorité du ticket
    public function getPrioriteTicket(): ?string
    {
        return $this->prioriteTicket;
    }

    // Définit la priorité du ticket
    public function setPrioriteTicket(?string $prioriteTicket): static
    {
        $this->prioriteTicket = $prioriteTicket;
        return $this;
    }

    // Retourne le statut du ticket
    public function getStatutTicket(): ?string
    {
        return $this->statutTicket;
    }

    // Définit le statut du ticket
    public function setStatutTicket(?string $statutTicket): static
    {
        $this->statutTicket = $statutTicket;
        return $this;
    }

    // Retourne la date de création du ticket
    public function getDateCreation(): ?\DateTime
    {
        return $this->dateCreation;
    }

    // Définit la date de création du ticket
    public function setDateCreation(?\DateTime $dateCreation): static
    {
        $this->dateCreation = $dateCreation;
        return $this;
    }

    // Retourne la date d'échéance du ticket
    public function getDateEcheance(): ?\DateTime
    {
        return $this->dateEcheance;
    }

    // Définit la date d'échéance du ticket
    public function setDateEcheance(?\DateTime $dateEcheance): static
    {
        $this->dateEcheance = $dateEcheance;
        return $this;
    }

    // Retourne la date de résolution du ticket
    public function getDateResolution(): ?\DateTime
    {
        return $this->dateResolution;
    }

    // Définit la date de résolution du ticket
    public function setDateResolution(?\DateTime $dateResolution): static
    {
        $this->dateResolution = $dateResolution;
        return $this;
    }

    // Retourne le projet associé à ce ticket
    public function getProjet(): ?Projet
    {
        return $this->projet;
    }

    // Définit le projet associé à ce ticket
    public function setProjet(?Projet $projet): static
    {
        $this->projet = $projet;
        return $this;
    }

    // Retourne l'utilisateur rapporteur du ticket
    public function getRapporteur(): ?User
    {
        return $this->rapporteur;
    }

    // Définit l'utilisateur rapporteur du ticket
    public function setRapporteur(?User $user): static
    {
        $this->rapporteur = $user;
        return $this;
    }

    // Retourne l'utilisateur développeur assigné au ticket
    public function getDeveloppeur(): ?User
    {
        return $this->developpeur;
    }

    // Définit l'utilisateur développeur assigné au ticket
    public function setDeveloppeur(?User $user): static
    {
        $this->developpeur = $user;
        return $this;
    }
}
