<x-filament::page>
    @include('filament.resources.students-resource.pages.details')

    <x-filament::card>
        <div>
            <h3 class="text-lg font-medium mb-4">{{ trans('common.TestResults') }}</h3>

            <div class="mt-6">
                <div class="p-4 bg-gray-50 rounded-lg">
                    {{ $this->table }}
                </div>
            </div>
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
