<?php

namespace App\Models;

use App\Enums\ContactStatus;
use App\Enums\ContactType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'name',
        'email',
        'phone',
        'area',
        'message',
        'preferred_date',
        'preferred_slot',
        'meeting_mode',
        'source',
        'ip_hash',
        'status',
        'notes',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'type' => ContactType::class,
            'status' => ContactStatus::class,
            'preferred_date' => 'date',
            'read_at' => 'datetime',
        ];
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', ContactStatus::Pending);
    }

    public function scopeAppointments(Builder $query): Builder
    {
        return $query->where('type', ContactType::Appointment);
    }

    public function slotLabel(): ?string
    {
        return config('despacho.appointment.slots.'.$this->preferred_slot);
    }

    public function modeLabel(): ?string
    {
        return config('despacho.appointment.modes.'.$this->meeting_mode);
    }
}
