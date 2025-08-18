<?php

namespace App\Filament\Widgets;

use App\Models\Todo;
use App\Models\TodoInvitation;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('總用戶數', User::count())
                ->description('註冊用戶總數')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
                
            Stat::make('ToDo 項目總數', Todo::count())
                ->description('所有 ToDo 項目')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('info'),
                
            Stat::make('已完成項目', Todo::where('status', 'completed')->count())
                ->description('已完成的 ToDo 項目')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
                
            Stat::make('待處理邀請', TodoInvitation::where('status', 'pending')->count())
                ->description('等待處理的邀請')
                ->descriptionIcon('heroicon-m-envelope')
                ->color('warning'),
                
            Stat::make('活躍協作', Todo::whereHas('collaborators')->count())
                ->description('有協作者的項目')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),
                
            Stat::make('本月新增項目', Todo::whereMonth('created_at', now()->month)->count())
                ->description('本月創建的項目')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),
        ];
    }
}
