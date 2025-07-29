<?php

namespace App\Enum;

enum EtatFacture: string
{
    case NON_PAYEE = 'Non payée';
    case PARTIELLEMENT_PAYEE = 'Partiellement payée';
    case PAYEE = 'Payée';

    public function label(): string
    {
        return match($this) {
            self::NON_PAYEE => 'Non payée',
            self::PARTIELLEMENT_PAYEE => 'Partiellement payée',
            self::PAYEE => 'Payée',
        };
    }

    public function badgeColor(): string
    {
        return match($this) {
            self::NON_PAYEE => 'danger',         // Rouge
            self::PARTIELLEMENT_PAYEE => 'warning', // Jaune
            self::PAYEE => 'success',           // Vert
        };
    }

    public function description(): string
    {
        return match($this) {
            self::NON_PAYEE => 'Aucune somme versée.',
            self::PARTIELLEMENT_PAYEE => 'Paiement partiel effectué.',
            self::PAYEE => 'Facture totalement réglée.',
        };
    }
}
