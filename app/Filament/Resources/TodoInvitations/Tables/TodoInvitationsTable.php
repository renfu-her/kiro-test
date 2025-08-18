<?php

namespace App\Filament\Resources\TodoInvitations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TodoInvitationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('todo.title')
                    ->label('ToDo 項目')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('inviter.name')
                    ->label('邀請者')
                    ->sortable(),
                TextColumn::make('invitee.name')
                    ->label('被邀請者')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('狀態')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'accepted' => 'success',
                        'rejected' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => '待處理',
                        'accepted' => '已接受',
                        'rejected' => '已拒絕',
                        default => $state,
                    })
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('邀請時間')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('更新時間')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('狀態')
                    ->options([
                        'pending' => '待處理',
                        'accepted' => '已接受',
                        'rejected' => '已拒絕',
                    ]),
                SelectFilter::make('inviter')
                    ->label('邀請者')
                    ->relationship('inviter', 'name'),
                SelectFilter::make('invitee')
                    ->label('被邀請者')
                    ->relationship('invitee', 'name'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
