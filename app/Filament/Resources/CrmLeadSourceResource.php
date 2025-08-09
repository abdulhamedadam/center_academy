<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\Settings;
use App\Filament\Resources\CrmLeadSourceResource\Pages;
use App\Models\CrmLeadSource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CrmLeadSourceResource extends Resource
{
    protected static ?string $model = CrmLeadSource::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $cluster = Settings::class;

    protected static ?int $navigationSort = 7;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function canViewAny(): bool
    {
        return true;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('common.name'))
                            ->required()
                            ->maxLength(255),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('common.name'))
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListCrmLeadSources::route('/'),
            'create' => Pages\CreateCrmLeadSource::route('/create'),
            'edit' => Pages\EditCrmLeadSource::route('/{record}/edit'),
        ];
    }

    // LOCALIZATION =====================================================================
    // LOCALIZATION =====================================================================
    // LOCALIZATION =====================================================================

    public static function getBreadCrumb(): string
    {
        return __('common.lead_source');
    }

    public static function getPluralLabel(): ?string
    {
        return __('common.lead_source');
    }

    public static function getLabel(): string
    {
        return __('common.lead_source');
    }

    public static function getModelLabel(): string
    {
        return __('common.lead_source');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.lead_source');
    }

    public static function getNavigationLabel(): string
    {
        return __('common.lead_source');
    }
} 