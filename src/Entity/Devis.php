<?php

namespace App\Entity;

use App\Enum\EtatDevis;
use App\Repository\DevisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DevisRepository::class)]
class Devis
{
    #[ORM\Id]
    #[ORM\Column(length: 20)]
    private ?string $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $contions = null;

    #[ORM\Column(enumType: EtatDevis::class)]
    private ?EtatDevis $etat = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;



    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'devisClient', cascade: ['persist'])]
    private ?Client $client = null;

    #[ORM\OneToOne(mappedBy: 'devisToFacture', targetEntity: Facture::class, cascade: ['persist', 'remove'])]
    private ?Facture $facture = null;

    /**
     * @var Collection<int, LigneDevis>
     */
    #[ORM\OneToMany(targetEntity: LigneDevis::class, mappedBy: 'devis')]
    private Collection $ligneDevis;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'devis', cascade: ['persist'])]
    private ?User $userDevis = null;


    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
        $this->id = 'DV' . $this->created_at->format('YmdHis') . strtoupper(bin2hex(random_bytes(4)));
        $this->ligneDevis = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getContions(): ?string
    {
        return $this->contions;
    }
    public function setContions(string $contions): static
    {
        $this->contions = $contions;
        return $this;
    }

    public function getEtat(): ?EtatDevis
    {
        return $this->etat;
    }
    public function setEtat(EtatDevis $etat): static
    {
        $this->etat = $etat;
        return $this;
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


    public function setFacture(?Facture $facture): static
    {
        $this->facture = $facture;
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

    public function getFacture(): ?Facture
    {
        return $this->facture;
    }

    /**
     * @return Collection<int, LigneDevis>
     */
    public function getLigneDevis(): Collection
    {
        return $this->ligneDevis;
    }

    public function addLigneDevi(LigneDevis $ligneDevi): static
    {
        if (!$this->ligneDevis->contains($ligneDevi)) {
            $this->ligneDevis->add($ligneDevi);
            $ligneDevi->setDevis($this);
        }

        return $this;
    }

    public function removeLigneDevi(LigneDevis $ligneDevi): static
    {
        if ($this->ligneDevis->removeElement($ligneDevi)) {
            if ($ligneDevi->getDevis() === $this) {
                $ligneDevi->setDevis(null);
            }
        }

        return $this;
    }

    public function getUserDevis(): ?User
    {
        return $this->userDevis;
    }

    public function setUserDevis(?User $userDevis): static
    {
        $this->userDevis = $userDevis;

        return $this;
    }

    public function getTotalHT(): float
    {
        $totalHT = 0;

        foreach ($this->getLigneDevis() as $ligne) {
            $quantite = $ligne->getQuantite();
            $prixUnitaireHT = $ligne->getPrixUnitaireHT();
            $totalHT += $quantite * $prixUnitaireHT;
        }

        return round($totalHT, 2);
    }
    public function getTotalTTC(): float
    {
        $totalTTC = 0;

        foreach ($this->getLigneDevis() as $ligne) {
            $quantite = $ligne->getQuantite();
            $prixUnitaireHT = $ligne->getPrixUnitaireHT();
            $tauxTVA = 1 / $ligne->getTva(); 

            $totalTTC += $quantite * $prixUnitaireHT * (1 + $tauxTVA);
        }

        return round($totalTTC, 2);
    }

    public function getTotalTVA(): float
    {
        return round($this->getTotalTTC() - $this->getTotalHT(), 2);
    }



}
