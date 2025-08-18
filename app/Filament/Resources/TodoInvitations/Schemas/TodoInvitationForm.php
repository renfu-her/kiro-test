<?php

namespace App\Filament\Resources\TodoInvitations\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TodoInvitationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('todo_id')
                    ->relationship('todo', 'title')
                    ->required(),
                Select::make('inviter_id')
                    ->relationship('inviter', 'name')
                    ->required(),
                Select::make('invitee_id')
                    ->relationship('invitee', 'name')
                    ->required(),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
            ]);
    }
}
