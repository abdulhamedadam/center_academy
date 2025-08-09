<?php

namespace App\Filament\Resources\CourseGroupResource\Pages;

use App\Filament\Resources\CourseGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCourseGroups extends ManageRecords
{
    protected static string $resource = CourseGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
