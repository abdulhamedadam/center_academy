<x-filament::page>
    @include('filament.resources.courses-resource.pages.details')

        <div>
            <h3 class="text-lg font-medium mb-4">{{trans('common.complaints')}}</h3>
            <div  style="margin-top: 10px">
                {{ $this->form }}
            </div>

            <div  style="margin-top: 20px">
                {{ $this->table }}
            </div>
        </div>

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
