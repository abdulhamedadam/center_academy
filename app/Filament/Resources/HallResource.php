<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\Settings;
use App\Filament\Resources\HallResource\Pages;
use App\Models\Hall;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HallResource extends Resource
{
    protected static ?string $model = Hall::class;
   
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    
    protected static ?string $cluster = Settings::class;
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->schema([
                        TextInput::make('name')
                            ->label(__('common.name'))
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1),

                        TextInput::make('capacity')
                            ->label(__('common.capacity'))
                            ->numeric()
                            ->minValue(1)
                            ->required()
                            ->columnSpan(1),
                    ])
                    ->columns(2),

                Textarea::make('description')
                    ->label(__('common.Description'))
                    ->maxLength(65535)
                    ->columnSpanFull()
                    ->rows(5),
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

                Tables\Columns\TextColumn::make('capacity')
                    ->label(__('common.capacity'))
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->label(__('common.Description'))
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    })
                    ->searchable(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageHalls::route('/'),
        ];
    }

    // LOCALIZATION =====================================================================
    public static function getBreadCrumb(): string
    {
        return __('common.halls');
    }

    public static function getPluralLabel(): ?string
    {
        return __('common.halls');
    }

    public static function getLabel(): string
    {
        return __('common.halls');
    }

    public static function getModelLabel(): string
    {
        return __('common.hall');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.halls');
    }

    public static function getNavigationLabel(): string
    {
        return __('common.halls');
    }
} 