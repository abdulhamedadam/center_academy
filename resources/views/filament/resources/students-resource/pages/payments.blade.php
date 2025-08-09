<x-filament::page>
    @include('filament.resources.students-resource.pages.details')

    <x-filament::card>
        <div>
            <x-filament::tabs>
                <x-filament::tabs.item
                    wire:click="$set('sub_tap', 'payments')"
                    active="{{ $sub_tap === 'payments' }}"
                >
                    {{ __('common.StudentPayments') }}
                </x-filament::tabs.item>

                <x-filament::tabs.item
                    wire:click="$set('sub_tap', 'installments')"
                    active="{{ $sub_tap === 'installments' }}"
                >
                    {{ __('common.Installments') }}
                </x-filament::tabs.item>

                <x-filament::tabs.item
                    wire:click="$set('sub_tap', 'payments_table')"
                    active="{{ $sub_tap === 'payments_table' }}"
                >
                    {{ __('common.PaymentsDetails') }}
                </x-filament::tabs.item>
            </x-filament::tabs>

            <div class="mt-6">
                @if($sub_tap === 'payments')
                    <div class="p-4 bg-gray-50 rounded-lg">
                        {{ $this->table }}
                    </div>
                @elseif($sub_tap === 'installments')
                    <div class="p-4 bg-gray-50 rounded-lg">
                        {{ $this->table }}
                    </div>
                @else
                    <div class="p-4 bg-gray-50 rounded-lg">
                        {{ $this->table }}
                    </div>
                @endif
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
