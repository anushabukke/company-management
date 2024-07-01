<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogResource\Pages;
use App\Models\ActivityLog;
use App\Models\Company;
use App\Models\Contact;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\SelectFilter;



class ActivityLogResource extends Resource
{
    protected static ?string $model = ActivityLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable()->searchable(),
                // TextColumn::make('log_name')->label('Log Name')->sortable()->searchable(),
                TextColumn::make('description')->label('Description')->sortable()->searchable(),
                TextColumn::make('subject_type')->label('Subject Type')->sortable()->searchable(),
                TextColumn::make('event')->label('Event')->sortable()->searchable(),
                // TextColumn::make('subject_id')->label('Subject ID')->sortable()->searchable(),
                // TextColumn::make('causer_type')->label('Causer Type')->sortable()->searchable(),

                TextColumn::make('subject_id')
                    ->label('Subject Name')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(function ($record) {
                        if ($record->subject_type === 'App\\Models\\Company') {
                            return $record->company->name;
                        } elseif ($record->subject_type === 'App\\Models\\Contact') {
                            return $record->contact->name;
                        }
                        return null;
                    })
                    ->tooltip(function ($record) {
                        if ($record->subject_type === 'App\\Models\\Company') {
                            return $record->company->name;
                        } elseif ($record->subject_type === 'App\\Models\\Contact') {
                            return $record->contact->name;
                        }
                        return null;
                    })
                    ->extraAttributes(['style' => 'max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;']),

                TextColumn::make('causer.name')->label('Causer Name')->sortable()->searchable(),
                TextColumn::make('Field')->label('Field'),
                TextColumn::make('Old')->label('Old'),
                TextColumn::make('New')->label('New')
                    ->extraAttributes(['style' => 'max-width: 180px; white-space: wrap; overflow: hidden; text-overflow: ellipsis;']),
                // TextColumn::make('batch_uuid')->label('Batch UUID')->sortable()->searchable(),
                TextColumn::make('updated_at')->label('Updated At')->dateTime()->sortable()->searchable(),
                TextColumn::make('created_at')->label('Created At')->dateTime()->sortable()->searchable(),
            ])

            ->filters([
                SelectFilter::make('causer_id')
                    ->options(static::getCauserNames())
                    ->label('Causer Name'),

                SelectFilter::make('company_id')
                    ->options(static::getCompanyNames())
                    ->label('Company Name')
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when($data['value'], function ($query, $value) {
                            $query->where('subject_type', Company::class)
                                ->where('subject_id', $value);
                        });
                    }),

                SelectFilter::make('contact_id')
                    ->options(static::getContactNames())
                    ->label('Contact Name')
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when($data['value'], function ($query, $value) {
                            $query->where('subject_type', Contact::class)
                                ->where('subject_id', $value);
                        });
                    }),

                Filter::make('date_range')
                    ->label('Date Range')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('From'),
                        Forms\Components\DatePicker::make('until')->label('Until'),
                    ])

                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->where(function ($query) use ($date) {
                                    $query->whereDate('updated_at', '>=', $date)
                                        ->orWhereDate('created_at', '>=', $date);
                                })
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->where(function ($query) use ($date) {
                                    $query->whereDate('updated_at', '<=', $date)
                                        ->orWhereDate('created_at', '<=', $date);
                                })
                            );
                    }),

            ],)
            ->actions([
                // Tables\Actions\Action::make('view'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
        ];
    }
    protected static function getCauserNames(): array
    {

        return User::pluck('name', 'id')->prepend('Any', '')->toArray();
    }
    protected static function getCompanyNames(): array
    {

        return Company::pluck('name', 'id')->prepend('Any', '')->toArray();
    }
    protected static function getContactNames(): array
    {

        return Contact::pluck('name', 'id')->prepend('Any', '')->toArray();
    }
}
