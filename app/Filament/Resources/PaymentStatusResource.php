<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\Settings;
use App\Filament\Resources\PaymentStatusResource\Pages;
use App\Models\PaymentStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentStatusResource extends Resource
{
    protected static ?string $model = PaymentStatus::class;
    protected static ?string $cluster = Settings::class;
    protected static ?int $navigationSort = 5;

    public static function canViewAny(): bool
    {
        return true;
    }

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->label(__('common.Name'))
                        ->required()
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('common.Name'))
                    ->sortable()
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
            'index' => Pages\ManagePaymentStatus::route('/'),
        ];
    }

    // LOCALIZATION =====================================================================
    public static function getBreadCrumb(): string
    {
        return __('common.payment_statuses');
    }

    public static function getPluralLabel(): ?string
    {
        return __('common.payment_statuses');
    }

    public static function getLabel(): string
    {
        return __('common.payment_statuses');
    }

    public static function getModelLabel(): string
    {
        return __('common.payment_status');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.payment_statuses');
    }

    public static function getNavigationLabel(): string
    {
        return __('common.payment_statuses');
    }
} 