<?php

namespace App\Enums;

enum ContactType: string
{
    case Contact = 'contact';
    case Appointment = 'appointment';

    public function label(): string
    {
        return match ($this) {
            self::Contact => 'Consulta',
            self::Appointment => 'Cita',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Contact => 'gray',
            self::Appointment => 'primary',
        };
    }
}
