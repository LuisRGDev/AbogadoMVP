<?php

namespace App\Filament\Resources\ContactResource\Pages;

use App\Enums\ContactStatus;
use App\Filament\Resources\ContactResource;
use App\Models\Contact;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewContact extends ViewRecord
{
    protected static string $resource = ContactResource::class;

    public function mount(int|string $record): void
    {
        parent::mount($record);

        if ($this->record->read_at === null) {
            $this->record->update(['read_at' => now()]);
        }
    }

    protected function getHeaderActions(): array
    {
        /** @var Contact $contact */
        $contact = $this->record;
        $digits = digits((string) $contact->phone);

        return [
            Actions\Action::make('reply')
                ->label('Responder por correo')
                ->icon('heroicon-o-envelope')
                ->url('mailto:'.$contact->email.'?subject='.rawurlencode('Re: su solicitud a '.site()->name()))
                ->color('primary'),
            Actions\Action::make('whatsapp')
                ->label('WhatsApp')
                ->icon('heroicon-o-chat-bubble-left-right')
                ->url('https://wa.me/'.$digits)
                ->openUrlInNewTab()
                ->color('success')
                ->visible($digits !== ''),
            Actions\Action::make('contacted')
                ->label('Marcar como contactado')
                ->icon('heroicon-o-check')
                ->color('info')
                ->visible($contact->status === ContactStatus::Pending)
                ->action(function () use ($contact): void {
                    $contact->update(['status' => ContactStatus::Contacted]);
                    $this->refreshFormData(['status']);
                }),
            Actions\Action::make('close')
                ->label('Cerrar')
                ->icon('heroicon-o-lock-closed')
                ->color('gray')
                ->requiresConfirmation()
                ->visible($contact->status !== ContactStatus::Closed)
                ->action(function () use ($contact): void {
                    $contact->update(['status' => ContactStatus::Closed]);
                    $this->refreshFormData(['status']);
                }),
            Actions\EditAction::make()->label('Estado y notas'),
        ];
    }
}
