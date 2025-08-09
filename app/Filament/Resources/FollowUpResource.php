<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FollowUpResource\Pages;
use App\Filament\Resources\FollowUpResource\RelationManagers;
use App\Models\CrmFollowUps;
use App\Models\FollowUp;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FollowUpResource extends Resource
{
    protected static ?string $model = CrmFollowUps::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?int $navigationSort = 14;
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getNavigationGroup(): ?string
    {
        return __('common.crm');
    }



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Select::make('lead_id')
                            ->label(__('common.Lead'))
                            ->options(\App\Models\CrmLeads::pluck('name', 'id'))
                            ->searchable()
                            ->required(),

                        Forms\Components\DatePicker::make('follow_up_date')
                            ->label(__('common.Follow-upDate'))
                            ->required()
                            ->minDate(now()),

                        Forms\Components\DatePicker::make('next_follow_up_date')
                            ->label(__('common.next_Follow-upDate'))
                            ->required()
                            ->minDate(now()),

                        Forms\Components\Select::make('result')
                            ->label(__('common.result'))
                            ->options([
                                CrmFollowUps::INTERSTED => __('common.Interested'),
                                CrmFollowUps::BUSY => __('common.Busy'),
                                CrmFollowUps::NO_ANSWER => __('common.NoAnswer'),
                                CrmFollowUps::WRONG_NUMBER => __('common.wrong_number'),
                                CrmFollowUps::NOT_INTERSTED => __('common.NotInterested'),
                            ])
                            ->required(),

                        Forms\Components\Select::make('communication_type')
                            ->label(__('common.communication_type'))
                            ->relationship('communicationType', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Textarea::make('note')
                            ->label(__('common.Notes'))
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('lead.name')
                    ->label(__('common.Lead'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('follow_up_date')
                    ->label(__('common.Follow-upDate'))
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('communicationType.name')
                    ->label(__('common.communication_type'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('next_follow_up_date')
                    ->label(__('common.next_Follow-upDate'))
                    ->date()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('result')
                    ->label(__('common.result'))
                    ->colors([
                        'success' => (string) CrmFollowUps::INTERSTED,
                        'warning' => (string) CrmFollowUps::BUSY,
                        'danger' => (string) CrmFollowUps::NO_ANSWER,
                        'danger' => (string) CrmFollowUps::WRONG_NUMBER,
                        'danger' => (string) CrmFollowUps::NOT_INTERSTED,
                    ])
                    ->formatStateUsing(fn ($state): string => match ($state) {
                        CrmFollowUps::INTERSTED => __('common.Interested'),
                        CrmFollowUps::BUSY => __('common.Busy'),
                        CrmFollowUps::NO_ANSWER => __('common.NoAnswer'),
                        CrmFollowUps::WRONG_NUMBER => __('common.wrong_number'),
                        CrmFollowUps::NOT_INTERSTED => __('common.NotInterested'),
                        default => __('common.Unknown'),
                    }),

                Tables\Columns\TextColumn::make('note')
                    ->label(__('common.Notes'))
                    ->limit(50),


            ])
            ->filters([
                Tables\Filters\SelectFilter::make('result')
                    ->options([
                        CrmFollowUps::INTERSTED => 'Interested',
                        CrmFollowUps::BUSY => 'Busy',
                        CrmFollowUps::NO_ANSWER => 'No Answer',
                        CrmFollowUps::WRONG_NUMBER => 'Wrong Number',
                        CrmFollowUps::NOT_INTERSTED => 'Not Interested',
                    ]),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('id', 'desc');
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageFollowUps::route('/'),
        ];
    }



    public static function getBreadCrumb(): string
    {
        return __('common.follow_up');
    }

    public static function getPluralLabel(): ?string
    {
        return __('common.follow_up');
    }

    public static function getLabel(): string
    {
        return __('common.follow_up');
    }

    public static function getModelLabel(): string
    {
        return __('common.add_follow_up');
    }

    public static function getPluralModelLabel(): string
    {
        return __('common.follow_up');
    }

    public static function getNavigationLabel(): string
    {
        return __('common.follow_up');
    }
}
