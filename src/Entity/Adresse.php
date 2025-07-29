<?php

namespace App\Entity;

use App\Repository\AdresseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdresseRepository::class)]
class Adresse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nomRue = null;

    #[ORM\Column]
    private ?int $codePostal = null;

    #[ORM\Column]
    private ?int $numeroLogement = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $batiment = null;

    #[ORM\ManyToOne(inversedBy: 'adresseUser')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * @var Collection<int, Client>
     */
    #[ORM\OneToMany(targetEntity: Client::class, mappedBy: 'adresseClient', orphanRemoval: true)]
    private Collection $clients;

    public function __construct()
    {
        $this->clients = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomRue(): ?string
    {
        return $this->nomRue;
    }

    public function setNomRue(string $nomRue): static
    {
        $this->nomRue = $nomRue;

        return $this;
    }

    public function getCodePostal(): ?int
    {
        return $this->codePostal;
    }

    public function setCodePostal(int $codePostal): static
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getNumeroLogement(): ?int
    {
        return $this->numeroLogement;
    }

    public function setNumeroLogement(int $numeroLogement): static
    {
        $this->numeroLogement = $numeroLogement;

        return $this;
    }

    public function getBatiment(): ?string
    {
        return $this->batiment;
    }

    public function setBatiment(?string $batiment): static
    {
        $this->batiment = $batiment;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Client>
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $client): static
    {
        if (!$this->clients->contains($client)) {
            $this->clients->add($client);
            $client->setAdresseClient($this);
        }

        return $this;
    }

    public function removeClient(Client $client): static
    {
        if ($this->clients->removeElement($client)) {
            // set the owning side to null (unless already changed)
            if ($client->getAdresseClient() === $this) {
                $client->setAdresseClient(null);
            }
        }

        return $this;
    }

 
}
