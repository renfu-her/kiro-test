<?php

namespace App\Filament\Resources\TodoInvitations\Pages;

use App\Filament\Resources\TodoInvitations\TodoInvitationResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTodoInvitation extends ViewRecord
{
    protected static string $resource = TodoInvitationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
