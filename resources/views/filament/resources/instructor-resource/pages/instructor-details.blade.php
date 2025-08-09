<x-filament::page>
    @include('filament.resources.instructor-resource.pages.details')


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

