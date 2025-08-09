<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentTransactionsResource\Pages;
use App\Models\PaymentTransactions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentTransactionsResource extends Resource
{
    protected static ?string $model = PaymentTransactions::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?int $navigationSort = 20;
    public static function getNavigationGroup(): string
    {
        return __('common.Financial');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.full_name')
                    ->label(__('common.student')),
                Tables\Columns\TextColumn::make('course.name')
                    ->label(__('common.course')),
                Tables\Columns\TextColumn::make('group.name')
                    ->label(__('common.group')),
                Tables\Columns\TextColumn::make('payment_date')
                    ->label(__('common.payment_date')),
                Tables\Columns\TextColumn::make('transaction_type')
                    ->label(__('common.transaction_type')),
                Tables\Columns\TextColumn::make('amount')
                    ->label(__('common.amount')),
            ])
            ->filters([
                // Add filters if needed
            ])
            ->actions([
                Tables\Actions\Action::make('printInvoice')
                    ->label('طباعة فاتورة')
                    ->icon('heroicon-o-printer')
                    ->url(fn($record) => route('payments.print-invoice', $record->id))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Define form fields if needed
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentTransactions::route('/'),
        ];
    }


    public static function getBreadCrumb(): string
    {
        return __('common.payment_transactions');
    }

    public static function getPluralLabel(): ?string
    {
        return __('common.payment_transactions');
    }

    public static function getLabel(): string
    {
        return __('common.payment_transactions');
    }

    public static function getModelLabel(): string
    {
        return __('common.payment_transaction');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.payment_transactions');
    }

    public static function getNavigationLabel(): string
    {
        return __('common.payment_transactions');
    }
} 