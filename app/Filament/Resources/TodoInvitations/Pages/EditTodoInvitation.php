<?php

namespace App\Filament\Resources\TodoInvitations\Pages;

use App\Filament\Resources\TodoInvitations\TodoInvitationResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTodoInvitation extends EditRecord
{
    protected static string $resource = TodoInvitationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
