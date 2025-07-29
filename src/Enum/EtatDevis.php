<?php

namespace App\Enum;

enum EtatDevis: string
{
    case EN_ATTENTE = 'en attente';
    case ACCEPTE = 'accepté';
    case REFUSE = 'refusé';

    public function label(): string
    {
        return match($this) {
            self::EN_ATTENTE => 'En attente',
            self::ACCEPTE => 'Accepté',
            self::REFUSE => 'Refusé',
        };
    }

    public function badgeColor(): string
    {
        return match($this) {
            self::EN_ATTENTE => 'secondary',
            self::ACCEPTE => 'success',
            self::REFUSE => 'danger',
        };
    }

    public function description(): string
    {
        return match($this) {
            self::EN_ATTENTE => 'Devis en cours de validation.',
            self::ACCEPTE => 'Devis accepté par le client.',
            self::REFUSE => 'Devis refusé par le client.',
        };
    }

    public static function fromLoose(string $value): self
    {
        return match(strtolower(trim($value))) {
            'en attente' => self::EN_ATTENTE,
            'accepte', 'accepté' => self::ACCEPTE,
            'refuse', 'refusé' => self::REFUSE,
            default => throw new \ValueError("Valeur invalide pour EtatDevis : $value")
        };
    }
}
