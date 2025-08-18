<?php

namespace App\Filament\Widgets;

use App\Models\Todo;
use Filament\Widgets\ChartWidget;

class TodoStatusChart extends ChartWidget
{
    protected ?string $heading = 'ToDo 狀態分佈';

    protected function getData(): array
    {
        $pendingCount = Todo::where('status', 'pending')->count();
        $inProgressCount = Todo::where('status', 'in_progress')->count();
        $completedCount = Todo::where('status', 'completed')->count();

        return [
            'datasets' => [
                [
                    'label' => 'ToDo 狀態',
                    'data' => [$pendingCount, $inProgressCount, $completedCount],
                    'backgroundColor' => [
                        'rgb(255, 205, 86)',  // 待處理 - 黃色
                        'rgb(54, 162, 235)',  // 進行中 - 藍色
                        'rgb(75, 192, 192)',  // 已完成 - 綠色
                    ],
                ],
            ],
            'labels' => ['待處理', '進行中', '已完成'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
