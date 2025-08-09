<x-filament::page>
    @include('filament.resources.students-resource.pages.details')

        <div>
            <h3 class="text-lg font-medium mb-4">{{trans('common.courses')}}</h3>

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
