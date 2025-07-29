<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    private ?string $prenom = null;

    #[ORM\Column(length: 100)]
    private ?string $mail = null;

    #[ORM\Column(length: 20)]
    private ?string $telephone = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $siret = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTime $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'clients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Adresse $adresseClient = null;

    /**
     * @var Collection<int, Facture>
     */
    #[ORM\OneToMany(targetEntity: Facture::class, mappedBy: 'client')]
    private Collection $facturesClient;

    /**
     * @var Collection<int, Devis>
     */
    #[ORM\OneToMany(targetEntity: Devis::class, mappedBy: 'client')]
    private Collection $devisClient;

    #[ORM\ManyToOne(inversedBy: 'clients')]
    private ?User $userClient = null;

    public function __construct()
    {
        $this->facturesClient = new ArrayCollection();
        $this->devisClient = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): static
    {
        $this->mail = $mail;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(?string $siret): static
    {
        $this->siret = $siret;

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

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getAdresseClient(): ?Adresse
    {
        return $this->adresseClient;
    }

    public function setAdresseClient(?Adresse $adresseClient): static
    {
        $this->adresseClient = $adresseClient;

        return $this;
    }

    /**
     * @return Collection<int, Facture>
     */
    public function getFacturesClient(): Collection
    {
        return $this->facturesClient;
    }

    public function addFacturesClient(Facture $facturesClient): static
    {
        if (!$this->facturesClient->contains($facturesClient)) {
            $this->facturesClient->add($facturesClient);
            $facturesClient->setClient($this);
        }

        return $this;
    }

    public function removeFacturesClient(Facture $facturesClient): static
    {
        if ($this->facturesClient->removeElement($facturesClient)) {
            // set the owning side to null (unless already changed)
            if ($facturesClient->getClient() === $this) {
                $facturesClient->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Devis>
     */
    public function getDevisClient(): Collection
    {
        return $this->devisClient;
    }

    public function addDevisClient(Devis $devisClient): static
    {
        if (!$this->devisClient->contains($devisClient)) {
            $this->devisClient->add($devisClient);
            $devisClient->setClient($this);
        }

        return $this;
    }

    public function removeDevisClient(Devis $devisClient): static
    {
        if ($this->devisClient->removeElement($devisClient)) {
            // set the owning side to null (unless already changed)
            if ($devisClient->getClient() === $this) {
                $devisClient->setClient(null);
            }
        }

        return $this;
    }

    public function getUserClient(): ?User
    {
        return $this->userClient;
    }

    public function setUserClient(?User $userClient): static
    {
        $this->userClient = $userClient;

        return $this;
    }

    public function __toString(): string
    {
        return $this->nom . ' ' . $this->prenom;
    }
 
}
