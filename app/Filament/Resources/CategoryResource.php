<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\Settings;
use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
   
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    protected static ?string $cluster = Settings::class;
    protected static ?int $navigationSort = 1;
//    protected static ?int $sort = 1;


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

                        // SpatieMediaLibraryFileUpload::make('category')
                        //     ->collection('category')
                        //     ->label(__('common.Image'))

                        //     ->image()
                        //     ->responsiveImages()
                        //     ->disk('public')
                        //     ->rules(['image', 'max:2048']),
                    ])
                    ->columns(2),

                Textarea::make('description')
                    ->label(__('common.Description'))
                    ->required()
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
                // SpatieMediaLibraryImageColumn::make('image')
                //     ->label(__('common.Image'))
                //     ->collection('category')
                //     ->toggleable(isToggledHiddenByDefault: true)
                //     ->disk('')
                //     ->circular(),
            ])
            ->filters([

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
            'index' => Pages\ManageCategories::route('/'),
        ];
    }



    // LOCALIZATION =====================================================================
    // LOCALIZATION =====================================================================
    // LOCALIZATION =====================================================================


    public static function getBreadCrumb(): string
    {
        return __('common.categories');
    }

    public static function getPluralLabel(): ?string
    {
        return __('common.categories');
    }

    public static function getLabel(): string
    {
        return __('common.categories');
    }

    public static function getModelLabel(): string
    {
        return __('common.category');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.categories');
    }

    public static function getNavigationLabel(): string
    {
        return __('common.categories');
    }
}
