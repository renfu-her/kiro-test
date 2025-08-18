<?php

namespace App\Filament\Resources\TodoInvitations\Pages;

use App\Filament\Resources\TodoInvitations\TodoInvitationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTodoInvitation extends CreateRecord
{
    protected static string $resource = TodoInvitationResource::class;
}
