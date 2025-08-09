<?php

namespace App\Filament\Resources\CoursesResource\Pages;

use App\Filament\Resources\CoursesResource;
use App\Models\City;
use App\Models\CourseMaterials;
use App\Models\Students;
use App\Services\CourseService;
use App\Services\StudentService;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class Materials extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static string $resource = CoursesResource::class;
    protected static string $view = 'filament.resources.courses-resource.pages.materials';

    public $files = [];
    public $record, $course,$from_id,$type;
    public $file_title;
    public $tap='materials';
    public $activeTab = 'all';

    protected CourseService $courseService;
    protected StudentService $studentService;

    public function mount($record)
    {
        $this->record = $record;
        $this->courseService = app(CourseService::class);
        $this->studentService = app(StudentService::class);
        $this->course = $this->courseService->get_course($record);
        $this->tableFilters = [];
    }

    public function getTitle(): string
    {
        return __('common.CourseStudents');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns($this->getTableColumns())
            ->headerActions($this->getCustomHeaderActions())
            ->actions($this->getTableActions())
          //  ->searchable()
            ->paginated([10, 25, 50, 100]);
    }

    protected function getTableQuery()
    {
        $query = CourseMaterials::query()->where('course_id', $this->record);

        if ($this->activeTab !== 'all') {
            $query->where('type', $this->activeTab);
        }

        if (isset($this->tableFilters['from_id'])) {
            if ($this->tableFilters['from_id'] === null) {
                $query->whereNull('from_id');
            } else {
                $query->where('from_id', $this->tableFilters['from_id']);
            }
        } else {
            $query->whereNull('from_id');
        }
        if (!empty($this->getTableSearch())) {
            $query->where('title', 'like', "%{$this->getTableSearch()}%");
        }

        return $query;
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('title')
                ->label(__('common.title'))
                ->searchable()
                ->sortable(),

            TextColumn::make('media.file_name')
                ->label(__('common.file'))
                ->visible(fn() => isset($this->tableFilters['from_id']) && $this->tableFilters['from_id'] !== null)
                ->formatStateUsing(function ($state, $record) {
                    $media = $record->getFirstMedia('materials');
                    if (!$media) {
                        return '';
                    }

                    return new \Illuminate\Support\HtmlString("
                    <a href='{$media->getUrl()}' target='_blank' style='cursor: pointer' title='".__('Click to open file')."'>
                        {$media->file_name}
                    </a>
                ");
                })
                ->html(),
        ];
    }

    protected function getCustomHeaderActions(): array
    {
        $parents = CourseMaterials::where('course_id', $this->record)
            ->whereNull('from_id')
            ->get();

        return [
            \Filament\Tables\Actions\Action::make('filter_tabs')
                ->label('')
                ->view('filament.resources.courses-resource.pages.materials-parent-tabs', [
                    'parents' => $parents,
                    'activeParent' => $this->tableFilters['from_id'] ?? null,
                ]),
        ];
    }

    public function setParentFilter($parentId = null): void
    {
        $this->tableFilters = ['from_id' => $parentId];
        $this->resetTableFiltersForm();
        $this->resetPage();
    }

    protected function getTableActions(): array
    {
        return [

            DeleteAction::make()
                ->label(__('common.delete'))
                ->icon('heroicon-o-trash')
                ->requiresConfirmation()
                ->action(function (CourseMaterials $record) {
                    $record->delete();
                    Notification::make()
                        ->title('Material deleted')
                        ->success()
                        ->send();
                }),
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            \Filament\Forms\Components\Card::make()
                ->schema([
                    \Filament\Forms\Components\Grid::make(3)
                        ->schema([
                            Select::make('from_id')
                                ->label(__('common.parent'))
                                ->options(
                                    CourseMaterials::whereNull('from_id')
                                        ->where('course_id', $this->record)
                                        ->whereNotNull('title')
                                        ->pluck('title', 'id')
                                        ->map(fn ($title) => $title ?? __('common.untitled'))
                                )
                                ->searchable()
                                ->live()
                                ->suffixAction(
                                    Action::make(__('common.category'))
                                        ->icon('heroicon-o-plus')
                                        ->form([
                                            TextInput::make('name')
                                                ->required()
                                                ->label(__('common.Name')),
                                        ])
                                        ->action(function (array $data) {
                                            $record = [
                                                'title' => $data['name'],
                                                'course_id' => $this->record,
                                                'from_id' => null,
                                                'type' => $this->activeTab !== 'all' ? $this->activeTab : 'documents',
                                            ];
                                            CourseMaterials::create($record);

                                            $this->dispatch('refresh');
                                        })
                                ),

                            TextInput::make('file_title')
                                ->label(__('common.title'))
                                ->required(),

                            Select::make('type')
                                ->label(__('common.type'))
                                ->options([
                                    'documents' => __('common.documents'),
                                    'videos' => __('common.videos'),
                                    'assignments' => __('common.assignments'),
                                ])
                                ->default(fn() => $this->activeTab !== 'all' ? $this->activeTab : 'documents')
                                ->required(),


                            SpatieMediaLibraryFileUpload::make('files')
                                ->collection('materials')
                                ->label(__('common.file'))
                                ->disk('public')
                                ->preserveFilenames()
                                ->acceptedFileTypes([
                                    'application/pdf',
                                    'application/msword',
                                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                    'video/mp4',
                                    'video/quicktime',
                                    'image/jpeg',
                                    'image/png',
                                ])
                                ->rules(['file', 'max:204800'])
                                ->multiple()
                                ->enableOpen()
                                ->enableDownload()
                                ->saveUploadedFileUsing(function (\Livewire\Features\SupportFileUploads\TemporaryUploadedFile $file, \Filament\Forms\Set $set, $state) {

                                    return $file;
                                }),


                            \Filament\Forms\Components\Actions::make([
                                \Filament\Forms\Components\Actions\Action::make('save')
                                    ->label(__('common.Add'))
                                    ->action(function (array $data) {
                                        $validatedData = $this->form->getState();
                                        $material = new CourseMaterials();
                                        $material->title = $validatedData['file_title'];
                                        $material->type = $validatedData['type'];
                                        $material->course_id = $this->record;
                                        $material->from_id = $validatedData['from_id'] ?? null;
                                        $material->save();

                                        if ($this->form->getComponent('files')->getState()) {
                                            foreach ($this->form->getComponent('files')->getState() as $file) {
                                                $material->addMedia($file->getRealPath())
                                                    ->usingFileName($file->getClientOriginalName())
                                                    ->toMediaCollection('materials');
                                            }
                                        }
                                        Notification::make()
                                            ->title('Material added successfully')
                                            ->success()
                                            ->send();
                                        $this->dispatch('refresh');
                                    })
                                    ->color('primary')
                                    ->icon('heroicon-o-plus')
                            ])
                                ->alignStart()
                                ->extraAttributes(['class' => 'mt-6'])
                        ])
                ])
        ];
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }
}
