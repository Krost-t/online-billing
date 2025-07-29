<?php

namespace App\Entity;

use App\Enum\EtatFacture;
use App\Repository\FactureAchatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FactureAchatRepository::class)]
class FactureAchat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pdf_path = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $date = null;

    #[ORM\ManyToOne(inversedBy: 'factureAchats')]
    private ?User $userFactureAchat = null;

  

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPdfPath(): ?string
    {
        return $this->pdf_path;
    }

    public function setPdfPath(?string $pdf_path): static
    {
        $this->pdf_path = $pdf_path;

        return $this;
    }

 
    public function getDatePaiement(): ?\DateTime
    {
        return $this->date;
    }

    public function setDatePaiement(?\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getUserFactureAchat(): ?User
    {
        return $this->userFactureAchat;
    }

    public function setUserFactureAchat(?User $userFactureAchat): static
    {
        $this->userFactureAchat = $userFactureAchat;

        return $this;
    }


}
