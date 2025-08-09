<?php

namespace App\Http\Livewire;

use App\Filament\Resources\CoursesResource;
use App\Models\CourseAssignmentsResults;
use App\Models\CourseTestsResults;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Pages\Page;
use Filament\Support\Contracts\TranslatableContentDriver;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Livewire\Component;

class AssignmentResultsTable extends Component implements HasTable,HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;
    public $assignment;
    protected $listeners = ['refreshResultsTable' => '$refresh'];

    protected function getTableQuery()
    {
       // dd($this->assignment);
        return CourseAssignmentsResults::where('assignment_id', $this->assignment)
            ->with('student');
    }
    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('student.full_name')
                ->label('Student'),
           TextColumn::make('grade')
                ->label('Grade'),
           TextColumn::make('feedback')
                ->label('Feedback')
                ->limit(30),

        ];
    }
    //------------------------------------------------------------------------------------------------------------------
    protected function getTableActions(): array
    {
        return [
            DeleteAction::make()
                ->action(function (CourseAssignmentsResults $record) {
                    $record->delete();
                })
                ->requiresConfirmation()
                ->modalHeading('Delete Result')
                ->modalSubheading('Are you sure you want to delete this test result? This action cannot be undone.')
                ->modalButton('Delete')
        ];
    }
    //------------------------------------------------------------------------------------------------------------------
    public function render()
    {
        return view('livewire.assignment_results_table');
    }
    //------------------------------------------------------------------------------------------------------------------

    public function makeFilamentTranslatableContentDriver(): ?TranslatableContentDriver
    {
        return null;
    }
}
