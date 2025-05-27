<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[Broadcast]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $client_id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomClient = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $emailClient = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numeroClient = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresseClient = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $referentClient = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClientId(): ?int
    {
        return $this->client_id;
    }

    public function setClientId(int $client_id): static
    {
        $this->client_id = $client_id;

        return $this;
    }

    public function getNomClient(): ?string
    {
        return $this->nomClient;
    }

    public function setNomClient(string $nomClient): static
    {
        $this->nomClient = $nomClient;

        return $this;
    }

    public function getEmailClient(): ?string
    {
        return $this->emailClient;
    }

    public function setEmailClient(?string $emailClient): static
    {
        $this->emailClient = $emailClient;

        return $this;
    }

    public function getNumeroClient(): ?string
    {
        return $this->numeroClient;
    }

    public function setNumeroClient(?string $numeroClient): static
    {
        $this->numeroClient = $numeroClient;

        return $this;
    }

    public function getAdresseClient(): ?string
    {
        return $this->adresseClient;
    }

    public function setAdresseClient(?string $adresseClient): static
    {
        $this->adresseClient = $adresseClient;

        return $this;
    }

    public function getReferentClient(): ?string
    {
        return $this->referentClient;
    }

    public function setReferentClient(?string $referentClient): static
    {
        $this->referentClient = $referentClient;

        return $this;
    }
}
