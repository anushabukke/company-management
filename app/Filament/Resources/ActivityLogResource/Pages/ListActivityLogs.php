<?php

namespace App\Filament\Resources\ActivityLogResource\Pages;

use App\Filament\Resources\ActivityLogResource;
use Carbon\Carbon;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ListRecords;


class ListActivityLogs extends ListRecords
{
    protected static string $resource = ActivityLogResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All')

                ->modifyQueryUsing(fn (Builder $query) => $query),
            'this_week' => Tab::make('This Week')
                ->modifyQueryUsing(fn (Builder $query) => $query->where(function ($query) {
                    $startOfWeek = Carbon::now()->startOfWeek();
                    $endOfWeek = Carbon::now()->endOfWeek();
                    $query->whereBetween('updated_at', [$startOfWeek, $endOfWeek])
                        ->orWhereBetween('created_at', [$startOfWeek, $endOfWeek]);
                })),
            'this_month' => Tab::make('This Month')
                ->modifyQueryUsing(fn (Builder $query) => $query->where(function ($query) {
                    $startOfMonth = Carbon::now()->startOfMonth();
                    $endOfMonth = Carbon::now()->endOfMonth();
                    $query->whereBetween('updated_at', [$startOfMonth, $endOfMonth])
                        ->orWhereBetween('created_at', [$startOfMonth, $endOfMonth]);
                })),
            'this_year' => Tab::make('This Year')
                ->modifyQueryUsing(fn (Builder $query) => $query->where(function ($query) {
                    $startOfYear = Carbon::now()->startOfYear();
                    $endOfYear = Carbon::now()->endOfYear();
                    $query->whereBetween('updated_at', [$startOfYear, $endOfYear])
                        ->orWhereBetween('created_at', [$startOfYear, $endOfYear]);
                })),

        ];
    }
}
