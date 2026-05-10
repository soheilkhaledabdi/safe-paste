<?php

namespace App\Filament\Widgets;

use App\Models\Paste;
use Filament\Widgets\ChartWidget;

class PasteActivityChart extends ChartWidget
{
    public function getHeading(): string
    {
        return __('safe_paste.admin.activity');
    }

    public function getDescription(): string
    {
        return __('safe_paste.admin.activity_description');
    }

    protected function getData(): array
    {
        $days = collect(range(13, 0))->map(fn (int $daysAgo) => now()->subDays($daysAgo)->startOfDay());

        return [
            'datasets' => [
                [
                    'label' => __('safe_paste.admin.created_dataset'),
                    'data' => $days
                        ->map(fn ($day): int => Paste::whereBetween('created_at', [$day, $day->copy()->endOfDay()])->count())
                        ->all(),
                    'borderColor' => '#14b8a6',
                    'backgroundColor' => 'rgba(20, 184, 166, 0.15)',
                ],
                [
                    'label' => __('safe_paste.admin.viewed_dataset'),
                    'data' => $days
                        ->map(fn ($day): int => Paste::whereBetween('last_viewed_at', [$day, $day->copy()->endOfDay()])->count())
                        ->all(),
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.15)',
                ],
            ],
            'labels' => $days->map(fn ($day): string => $day->format('M j'))->all(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
