<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExpenseResource\Pages;
use App\Models\Expense;
use App\Models\ExpenseItems;
use App\Models\Expenses;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ExpenseResource extends Resource
{
    protected static ?string $model = Expenses::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?int $navigationSort = 8;
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getNavigationGroup(): string
    {
        return __('common.Financial');
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('band_id')
                ->label(__('common.ExpenseItem'))
                    ->relationship('band', 'name')
                    ->preload()
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('amount')
                    ->label(__('common.Amount'))
                    ->numeric()
                    ->required(),

                Forms\Components\DatePicker::make('expense_date')
                    ->label(__('common.Date'))
                    ->required()
                    ->default(now()),

                Forms\Components\Textarea::make('description')
                    ->label(__('common.Description'))
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('notes')
                    ->label(__('common.Notes'))
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('band.name')
                    ->label(__('common.ExpenseItem'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label(__('common.Amount'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('expense_date')
                    ->label(__('common.Date'))
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->label(__('common.Description'))
                    ->limit(30),

                Tables\Columns\TextColumn::make('notes')
                    ->label(__('common.Notes'))
                    ->limit(30),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('expense_item')
                    ->label(__('common.FilterByItem'))
                    ->relationship('band', 'name'),

                Tables\Filters\Filter::make('expense_date')
                    ->label(__('common.FilterByDate'))
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label(__('common.FromDate')),
                        Forms\Components\DatePicker::make('to')
                            ->label(__('common.ToDate')),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn($q) => $q->whereDate('expense_date', '>=', $data['from']))
                            ->when($data['to'], fn($q) => $q->whereDate('expense_date', '<=', $data['to']));
                    })
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
            'index' => Pages\ManageExpenses::route('/'),

        ];
    }



    public static function getBreadCrumb(): string
    {
        return __('common.Expenses');
    }

    public static function getPluralLabel(): ?string
    {
        return __('common.Expenses');
    }

    public static function getLabel(): string
    {
        return __('common.Expenses');
    }

    public static function getModelLabel(): string
    {
        return __('common.Expense');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.Expenses');
    }

    public static function getNavigationLabel(): string
    {
        return __('common.Expenses');
    }
}
