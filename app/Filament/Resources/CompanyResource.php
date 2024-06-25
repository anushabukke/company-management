<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyResource\Pages;
use App\Models\Company;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('entity_no')->required()->maxLength(125),
                DatePicker::make('date_of_incorporation')->required(),
                TextInput::make('name')->required()->maxLength(125),
                TextInput::make('entity_no_and_name')->nullable()->maxLength(125),
                TextInput::make('jurisdiction_id')->required()->numeric(),
                TextInput::make('status')->required()->maxLength(125),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('entity_no')->label('Entity No')->searchable(),
                TextColumn::make('date_of_incorporation')->label('Date of Incorporation')->date()->searchable(),
                TextColumn::make('name')->label('Name')->searchable(),
                TextColumn::make('entity_no_and_name')->label('Entity No and Name')->searchable(),
                TextColumn::make('jurisdiction_id')->label('Jurisdiction ID')->searchable(),
                TextColumn::make('status')->label('Status')->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('activities')->url(fn ($record) => static::getUrl('activities', ['record' => $record])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'activities' => Pages\ListCompanyActivities::route('/{record}/activities'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }
}
