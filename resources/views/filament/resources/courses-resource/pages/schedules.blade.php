<x-filament::page>
    @include('filament.resources.courses-resource.pages.details')

    <x-filament::card>
        <div>
            <h3 class="text-lg font-medium mb-4">{{__('common.schedules')}}</h3>
            <div style="margin-top: 10px">
              {{$this->table}}
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
