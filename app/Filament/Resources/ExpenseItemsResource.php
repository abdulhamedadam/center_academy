<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\Settings;
use App\Filament\Resources\ExpenseItemsResource\Pages;
use App\Filament\Resources\ExpenseItemsResource\RelationManagers;
use App\Models\ExpenseItems;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExpenseItemsResource extends Resource
{
    protected static ?string $model = ExpenseItems::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $cluster = Settings::class;
    protected static ?int $navigationSort = 3;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
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
            'index' => Pages\ManageExpenseItems::route('/'),
        ];
    }


    public static function getBreadCrumb(): string
    {
        return __('common.expense_items');
    }

    public static function getPluralLabel(): ?string
    {
        return __('common.expense_items');
    }

    public static function getLabel(): string
    {
        return __('common.expense_items');
    }

    public static function getModelLabel(): string
    {
        return __('common.expense_item');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.expense_items');
    }

    public static function getNavigationLabel(): string
    {
        return __('common.expense_items');
    }
}
