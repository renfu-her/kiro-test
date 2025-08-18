<?php

namespace App\Filament\Resources\TodoInvitations\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TodoInvitationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('todo.title')
                    ->numeric(),
                TextEntry::make('inviter.name')
                    ->numeric(),
                TextEntry::make('invitee.name')
                    ->numeric(),
                TextEntry::make('status'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
