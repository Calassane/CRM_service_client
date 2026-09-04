<?php

namespace App\Enums;

enum ReservationStatus: string
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Ongoing = 'ongoing';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'En attente',
            self::Confirmed => 'Confirmée',
            self::Ongoing => 'En cours',
            self::Completed => 'Terminée',
            self::Cancelled => 'Annulée',
        };
    }
}
