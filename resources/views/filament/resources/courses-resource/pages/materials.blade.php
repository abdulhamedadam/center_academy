<x-filament::page>
    @include('filament.resources.courses-resource.pages.details')

    <x-filament::card>
        <!-- Professional Tabs Implementation -->
        <div class="border-b">
            <nav class="flex space-x-4 px-4 py-3" aria-label="Materials Navigation">
                <button type="button"
                        class="px-3 py-2 text-sm font-medium rounded-md @if($activeTab == 'all') bg-primary-500 text-white @else text-gray-700 hover:text-gray-900 hover:bg-gray-100 @endif"
                        wire:click="$set('activeTab', 'all')">
                    All Materials
                </button>
                <button type="button"
                        class="px-3 py-2 text-sm font-medium rounded-md @if($activeTab == 'documents') bg-primary-500 text-white @else text-gray-700 hover:text-gray-900 hover:bg-gray-100 @endif"
                        wire:click="$set('activeTab', 'documents')">
                    Documents
                </button>
                <button type="button"
                        class="px-3 py-2 text-sm font-medium rounded-md @if($activeTab == 'videos') bg-primary-500 text-white @else text-gray-700 hover:text-gray-900 hover:bg-gray-100 @endif"
                        wire:click="$set('activeTab', 'videos')">
                    Videos
                </button>
                <button type="button"
                        class="px-3 py-2 text-sm font-medium rounded-md @if($activeTab == 'assignments') bg-primary-500 text-white @else text-gray-700 hover:text-gray-900 hover:bg-gray-100 @endif"
                        wire:click="$set('activeTab', 'assignments')">
                    Assignments
                </button>
            </nav>
        </div>

        <!-- Add New Form -->
        <div class="p-4 border-b">
            <h3 class="text-lg font-medium mb-4">Add New Material</h3>
            {{ $this->form }}
        </div>


        <div class="p-4">
            {{ $this->table }}
        </div>
    </x-filament::card>
</x-filament::page>
<script>

    document.addEventListener('livewire:init', () => {
        Livewire.on('refresh', () => {
            console.log('Refresh event received');
            window.location.reload();
        });
    });

    document.addEventListener('refresh', () => {
        console.log('Refresh event received');
        window.location.reload();
    });
</script>

