<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?int $navigationSort = 20;
    
    // public static function getNavigationGroup(): string
    // {
    //     return __('common.general');
    // }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('common.name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label(__('common.email'))
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('current_password')
                    ->label(__('common.current_password'))
                    ->password()
                    ->dehydrated(false)
                    ->required(fn ($livewire) => $livewire instanceof Pages\EditUser)
                    ->rule('current_password'),
                Forms\Components\TextInput::make('password')
                    ->label(__('common.new_password'))
                    ->password()
                    ->required(fn ($livewire) => $livewire instanceof Pages\CreateUser)
                    ->minLength(8)
                    ->same('password_confirmation')
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn ($livewire) => $livewire instanceof Pages\CreateUser),
                Forms\Components\TextInput::make('password_confirmation')
                    ->label(__('common.confirm_password'))
                    ->password()
                    ->required(fn ($livewire) => $livewire instanceof Pages\CreateUser)
                    ->minLength(8)
                    ->dehydrated(false),
                Forms\Components\Select::make('roles')
                    ->label(__('common.roles'))
                    ->multiple()
                    ->relationship('roles', 'name')
                    ->preload(),
                Forms\Components\Toggle::make('is_active')
                    ->label(__('common.is_active'))
                    ->default(1)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('common.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('common.email'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label(__('common.roles'))
                    ->badge(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('common.is_active'))
                    ->boolean()
                    ->getStateUsing(fn ($record) => $record->is_active === 1)
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('common.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('common.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    // LOCALIZATION =====================================================================
    public static function getBreadCrumb(): string
    {
        return __('common.users');
    }

    public static function getPluralLabel(): ?string
    {
        return __('common.users');
    }

    public static function getLabel(): string
    {
        return __('common.users');
    }

    public static function getModelLabel(): string
    {
        return __('common.user');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.users');
    }

    public static function getNavigationLabel(): string
    {
        return __('common.users');
    }
}
