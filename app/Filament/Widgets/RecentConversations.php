<?php

// app/Filament/Widgets/RecentConversations.php
namespace App\Filament\Widgets;

use App\Models\CrmFollowUps;
use Filament\Tables;
use Filament\Tables\Table; // ← Correct import
use Filament\Widgets\TableWidget as BaseWidget;

class RecentConversations extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                CrmFollowUps::query()
                    ->with(['lead', 'user'])
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('lead.name')
                    ->label(__('common.Lead')),

                Tables\Columns\TextColumn::make('note')
                    ->label(__('common.Conversation'))
                    ->limit(50),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('common.Date'))
                    ->date(),
            ]);
    }


    protected function getTableHeading(): string
    {
        return __('common.RecentConversations');
    }


}
