<?php

namespace App\Http\Livewire;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Contracts\TranslatableContentDriver;
use Filament\Tables\Actions\DeleteAction;
use Livewire\Component;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Models\CourseTestsResults;

class ResultsTable extends Component implements HasTable,HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;
    public $testId;
    protected $listeners = ['refreshResultsTable' => '$refresh'];
    protected function getTableQuery()
    {
        return CourseTestsResults::where('test_id', $this->testId)->with('student');
    }
    //------------------------------------------------------------------------------------------------------------------

    protected function getTableColumns(): array
    {
        return [
            \Filament\Tables\Columns\TextColumn::make('student.full_name')
                ->label('Student'),
            \Filament\Tables\Columns\TextColumn::make('grade')
                ->label('Grade'),
            \Filament\Tables\Columns\TextColumn::make('feedback')
                ->label('Feedback')
                ->limit(30),

        ];
    }

    //------------------------------------------------------------------------------------------------------------------

    protected function getTableActions(): array
    {
        return [
            DeleteAction::make()
                ->action(function (CourseTestsResults $record) {
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
        return view('livewire.results-table');
    }
    //------------------------------------------------------------------------------------------------------------------

    public function makeFilamentTranslatableContentDriver(): ?TranslatableContentDriver
    {
        return null;
    }
}
