<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\Settings;
use App\Filament\Resources\CrmLeadsStatusResource\Pages;
use App\Models\CrmLeadsStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CrmLeadsStatusResource extends Resource
{
    protected static ?string $model = CrmLeadsStatus::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $cluster = Settings::class;
    protected static ?int $navigationSort = 6;
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
                        Forms\Components\Select::make('color')
                            ->label(__('common.color'))
                            ->options([
                                'primary' => __('common.primary'),
                                'secondary' => __('common.secondary'),
                                'success' => __('common.success'),
                                'danger' => __('common.danger'),
                                'warning' => __('common.warning'),
                                'info' => __('common.info'),
                            ])
                            ->required(),
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
                Tables\Columns\TextColumn::make('color')
                    ->label(__('common.color'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'primary' => 'primary',
                        'secondary' => 'secondary',
                        'success' => 'success',
                        'danger' => 'danger',
                        'warning' => 'warning',
                        'info' => 'info',
                        default => 'gray',
                    }),
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
            'index' => Pages\ListCrmLeadsStatuses::route('/'),
            //'create' => Pages\CreateCrmLeadsStatus::route('/create'),
          //  'edit' => Pages\EditCrmLeadsStatus::route('/{record}/edit'),
        ];
    }


    // LOCALIZATION =====================================================================
    // LOCALIZATION =====================================================================
    // LOCALIZATION =====================================================================


    public static function getBreadCrumb(): string
    {
        return __('common.leads_status');
    }

    public static function getPluralLabel(): ?string
    {
        return __('common.leads_status');
    }

    public static function getLabel(): string
    {
        return __('common.leads_status');
    }

    public static function getModelLabel(): string
    {
        return __('common.lead_status');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.leads_status');
    }

    public static function getNavigationLabel(): string
    {
        return __('common.leads_status');
    }
} 