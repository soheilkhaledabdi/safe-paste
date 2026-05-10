<?php

namespace App\Filament\Widgets;

use App\Models\Paste;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalPastes = Paste::count();
        $guestPastes = Paste::whereNull('user_id')->count();
        $activePastes = Paste::query()
            ->where(function ($query): void {
                $query->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->where(function ($query): void {
                $query->where('burn_after_reading', false)->orWhereNull('read_at');
            })
            ->where(function ($query): void {
                $query->whereNull('max_views')->orWhereColumn('views_count', '<', 'max_views');
            })
            ->count();

        return [
            Stat::make(__('safe_paste.dashboard.total_pastes'), number_format($totalPastes))
                ->description(__('safe_paste.admin.created_24h', ['count' => number_format(Paste::where('created_at', '>=', now()->subDay())->count())]))
                ->color('primary'),
            Stat::make(__('safe_paste.dashboard.active_pastes'), number_format($activePastes))
                ->description(__('safe_paste.admin.expired_count', ['count' => number_format(Paste::whereNotNull('expires_at')->where('expires_at', '<=', now())->count())]))
                ->color('success'),
            Stat::make(__('safe_paste.dashboard.total_views'), number_format((int) Paste::sum('views_count')))
                ->description(__('safe_paste.admin.viewed_24h', ['count' => number_format(Paste::whereNotNull('last_viewed_at')->where('last_viewed_at', '>=', now()->subDay())->count())]))
                ->color('info'),
            Stat::make(__('safe_paste.admin.users'), number_format(User::count()))
                ->description(__('safe_paste.admin.admins_count', ['count' => number_format(User::where('is_admin', true)->count())]))
                ->color('warning'),
            Stat::make(__('safe_paste.admin.guest_paste'), number_format($guestPastes))
                ->description($totalPastes > 0 ? __('safe_paste.admin.guest_percent', ['percent' => round(($guestPastes / $totalPastes) * 100)]) : __('safe_paste.admin.no_pastes_yet'))
                ->color('gray'),
            Stat::make(__('safe_paste.admin.protected_pastes'), number_format(Paste::whereNotNull('password_hash')->count()))
                ->description(__('safe_paste.admin.burn_count', ['count' => number_format(Paste::where('burn_after_reading', true)->count())]))
                ->color('danger'),
        ];
    }
}
