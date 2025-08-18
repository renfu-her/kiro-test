<?php

namespace App\Filament\Resources\Todos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TodosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('標題')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('狀態')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'in_progress' => 'info',
                        'completed' => 'success',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => '待處理',
                        'in_progress' => '進行中',
                        'completed' => '已完成',
                        default => $state,
                    })
                    ->sortable(),
                TextColumn::make('owner.name')
                    ->label('擁有者')
                    ->sortable(),
                TextColumn::make('collaborators_count')
                    ->label('協作者數量')
                    ->counts('collaborators')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('創建時間')
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
                        'in_progress' => '進行中',
                        'completed' => '已完成',
                    ]),
                SelectFilter::make('owner')
                    ->label('擁有者')
                    ->relationship('owner', 'name'),
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
