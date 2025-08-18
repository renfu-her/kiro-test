<?php

namespace App\Filament\Resources\TodoInvitations;

use App\Filament\Resources\TodoInvitations\Pages\CreateTodoInvitation;
use App\Filament\Resources\TodoInvitations\Pages\EditTodoInvitation;
use App\Filament\Resources\TodoInvitations\Pages\ListTodoInvitations;
use App\Filament\Resources\TodoInvitations\Pages\ViewTodoInvitation;
use App\Filament\Resources\TodoInvitations\Schemas\TodoInvitationForm;
use App\Filament\Resources\TodoInvitations\Schemas\TodoInvitationInfolist;
use App\Filament\Resources\TodoInvitations\Tables\TodoInvitationsTable;
use App\Models\TodoInvitation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TodoInvitationResource extends Resource
{
    protected static ?string $model = TodoInvitation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $navigationLabel = '邀請管理';

    protected static ?string $modelLabel = '邀請';

    protected static ?string $pluralModelLabel = '邀請';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return TodoInvitationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TodoInvitationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TodoInvitationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTodoInvitations::route('/'),
            'create' => CreateTodoInvitation::route('/create'),
            'view' => ViewTodoInvitation::route('/{record}'),
            'edit' => EditTodoInvitation::route('/{record}/edit'),
        ];
    }
}
