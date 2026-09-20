<?php

namespace App\Enums;

enum ContactStatus: string
{
    case Pending = 'pending';
    case Contacted = 'contacted';
    case Closed = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pendiente',
            self::Contacted => 'Contactado',
            self::Closed => 'Cerrado',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Contacted => 'info',
            self::Closed => 'success',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn (self $case): array => [$case->value => $case->label()])->all();
    }
}
