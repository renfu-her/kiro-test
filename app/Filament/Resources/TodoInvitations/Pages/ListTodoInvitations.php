<?php

namespace App\Filament\Resources\TodoInvitations\Pages;

use App\Filament\Resources\TodoInvitations\TodoInvitationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTodoInvitations extends ListRecords
{
    protected static string $resource = TodoInvitationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
