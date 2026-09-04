<?php

namespace App\Enums;

enum CallReason: string
{
    case Reservation = 'reservation';
    case Complaint = 'complaint';
    case TechnicalSupport = 'technical_support';
    case Payment = 'payment';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Reservation => 'Réservation',
            self::Complaint => 'Réclamation',
            self::TechnicalSupport => 'Support technique',
            self::Payment => 'Paiement',
            self::Other => 'Autre',
        };
    }
}
