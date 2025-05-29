<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[Broadcast]
// Entité représentant un client dans le système
class Client
{
    // Identifiant unique de la table (clé primaire)
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Identifiant métier du client (différent de l'id technique)
    #[ORM\Column]
    private ?int $client_id = null;

    // Nom du client
    #[ORM\Column(length: 255)]
    private ?string $nomClient = null;

    // Adresse email du client (optionnelle)
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $emailClient = null;

    // Numéro de téléphone du client (optionnel)
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numeroClient = null;

    // Adresse postale du client (optionnelle)
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresseClient = null;

    // Nom du référent du client (optionnel)
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $referentClient = null;

    // Liste des projets associés à ce client
    #[ORM\OneToMany(mappedBy: "client", targetEntity: Projet::class)]
    private Collection $projets;

    // Constructeur : initialise la collection de projets
    public function __construct()
    {
        $this->projets = new ArrayCollection();
    }

    // Retourne l'identifiant technique du client
    public function getId(): ?int
    {
        return $this->id;
    }

    // Retourne l'identifiant métier du client
    public function getClientId(): ?int
    {
        return $this->client_id;
    }

    // Définit l'identifiant métier du client
    public function setClientId(int $client_id): static
    {
        $this->client_id = $client_id;
        return $this;
    }

    // Retourne le nom du client
    public function getNomClient(): ?string
    {
        return $this->nomClient;
    }

    // Définit le nom du client
    public function setNomClient(string $nomClient): static
    {
        $this->nomClient = $nomClient;
        return $this;
    }

    // Retourne l'email du client
    public function getEmailClient(): ?string
    {
        return $this->emailClient;
    }

    // Définit l'email du client
    public function setEmailClient(?string $emailClient): static
    {
        $this->emailClient = $emailClient;
        return $this;
    }

    // Retourne le numéro de téléphone du client
    public function getNumeroClient(): ?string
    {
        return $this->numeroClient;
    }

    // Définit le numéro de téléphone du client
    public function setNumeroClient(?string $numeroClient): static
    {
        $this->numeroClient = $numeroClient;
        return $this;
    }

    // Retourne l'adresse du client
    public function getAdresseClient(): ?string
    {
        return $this->adresseClient;
    }

    // Définit l'adresse du client
    public function setAdresseClient(?string $adresseClient): static
    {
        $this->adresseClient = $adresseClient;
        return $this;
    }

    // Retourne le nom du référent du client
    public function getReferentClient(): ?string
    {
        return $this->referentClient;
    }

    // Définit le nom du référent du client
    public function setReferentClient(?string $referentClient): static
    {
        $this->referentClient = $referentClient;
        return $this;
    }

    // Retourne la collection des projets associés à ce client
    public function getProjets(): Collection
    {
        return $this->projets;
    }
}
