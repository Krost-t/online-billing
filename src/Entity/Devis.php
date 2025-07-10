<?php

namespace App\Entity;

use App\Repository\DevisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User; // <-- Ajouté, adapte le namespace si besoin

#[ORM\Entity(repositoryClass: DevisRepository::class)]
class Devis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $clientNom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $clientEmail = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 50)]
    private ?string $statut = null;

    /**
     * @var Collection<int, LigneDevis>
     */
    #[ORM\OneToMany(targetEntity: LigneDevis::class, mappedBy: 'devis', cascade:["persist", "remove"], orphanRemoval: true)]
    private Collection $lignes;

    
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true)]  // nullable=true si user facultatif, sinon mettre false
    private ?User $user = null;

    public function __construct()
    {
        $this->lignes = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();  // Initialisation automatique
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClientNom(): ?string
    {
        return $this->clientNom;
    }

    public function setClientNom(string $clientNom): static
    {
        $this->clientNom = $clientNom;
        return $this;
    }

    public function getClientEmail(): ?string
    {
        return $this->clientEmail;
    }

    public function setClientEmail(?string $clientEmail): static
    {
        $this->clientEmail = $clientEmail;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;
        return $this;
    }

    /**
     * @return Collection<int, LigneDevis>
     */
    public function getLignes(): Collection
    {
        return $this->lignes;
    }

    public function addLigne(LigneDevis $ligne): static
    {
        if (!$this->lignes->contains($ligne)) {
            $this->lignes->add($ligne);
            $ligne->setDevis($this);
        }
        return $this;
    }

    public function removeLigne(LigneDevis $ligne): static
    {
        if ($this->lignes->removeElement($ligne)) {
            if ($ligne->getDevis() === $this) {
                $ligne->setDevis(null);
            }
        }
        return $this;
    }

    // ** Getter et setter pour user **
    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }
}
