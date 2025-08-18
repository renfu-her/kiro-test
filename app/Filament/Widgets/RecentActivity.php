<?php

namespace App\Filament\Widgets;

use App\Models\Todo;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentActivity extends TableWidget
{
    protected static ?string $heading = '最近活動';

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Todo::query()->latest()->limit(10))
            ->columns([
                TextColumn::make('title')
                    ->label('項目標題')
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('owner.name')
                    ->label('擁有者')
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
                    }),
                TextColumn::make('collaborators_count')
                    ->label('協作者')
                    ->counts('collaborators'),
                TextColumn::make('created_at')
                    ->label('創建時間')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated(false);
    }
}
