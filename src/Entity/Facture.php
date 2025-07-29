<?php

namespace App\Entity;

use App\Enum\EtatFacture;
use App\Repository\FactureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FactureRepository::class)]
class Facture
{
    #[ORM\Id]
    #[ORM\Column(length: 20)]
    private ?string $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'facturesClient',   cascade: ['persist'])]
    private ?Client $client = null;

    /**
     * @var Collection<int, LigneFacture>
     */
    #[ORM\OneToMany(targetEntity: LigneFacture::class, mappedBy: 'facture')]
    private Collection $ligneFactures;

    #[ORM\ManyToOne(inversedBy: 'factures')]
    private ?User $userFacture = null;

    #[ORM\OneToOne(inversedBy: 'facture', cascade: ['persist', 'remove'])]
    private ?Devis $devisToFacture = null;

    #[ORM\Column(enumType: EtatFacture::class)]
    private ?EtatFacture $statutPayement = null;

    #[ORM\Column(nullable: true)]
    private ?float $total_ht = null;

    #[ORM\Column(nullable: true)]
    private ?float $total_tva = null;

    #[ORM\Column(nullable: true)]
    private ?float $total_ttc = null;

    
    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
        $this->id = 'FT' . $this->created_at->format('YmdHis') . strtoupper(bin2hex(random_bytes(4)));
        $this->ligneFactures = new ArrayCollection();

    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return Collection<int, LigneFacture>
     */
    public function getLigneFactures(): Collection
    {
        return $this->ligneFactures;
    }

    public function addLigneFacture(LigneFacture $ligneFacture): static
    {
        if (!$this->ligneFactures->contains($ligneFacture)) {
            $this->ligneFactures->add($ligneFacture);
            $ligneFacture->setFacture($this);
        }

        return $this;
    }

    public function removeLigneFacture(LigneFacture $ligneFacture): static
    {
        if ($this->ligneFactures->removeElement($ligneFacture)) {
            // set the owning side to null (unless already changed)
            if ($ligneFacture->getFacture() === $this) {
                $ligneFacture->setFacture(null);
            }
        }

        return $this;
    }

    public function getUserFacture(): ?User
    {
        return $this->userFacture;
    }

    public function setUserFacture(?User $userFacture): static
    {
        $this->userFacture = $userFacture;

        return $this;
    }

    public function getDevisToFacture(): ?Devis
    {
        return $this->devisToFacture;
    }

    public function setDevisToFacture(?Devis $devisToFacture): static
    {
        $this->devisToFacture = $devisToFacture;

        return $this;
    }

    public function getStatutPayement(): ?EtatFacture
    {
        return $this->statutPayement;
    }

    public function setStatutPayement(EtatFacture $statutPayement): static
    {
        $this->statutPayement = $statutPayement;

        return $this;
    }

    public function getTotalHt(): ?float
    {
        return $this->total_ht;
    }

    public function setTotalHt(?float $total_ht): static
    {
        $this->total_ht = $total_ht;

        return $this;
    }

    public function getTotalTva(): ?float
    {
        return $this->total_tva;
    }

    public function setTotalTva(?float $total_tva): static
    {
        $this->total_tva = $total_tva;

        return $this;
    }

    public function getTotalTtc(): ?float
    {
        return $this->total_ttc;
    }

    public function setTotalTtc(?float $total_ttc): static
    {
        $this->total_ttc = $total_ttc;

        return $this;
    }


}
