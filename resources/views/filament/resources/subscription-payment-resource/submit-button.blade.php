@php
    $button = $getAction();
@endphp

<x-filament::button
    :form="$getForm()"
    :type="$button->getType()"
    :wire:click="$button->getLivewireClickHandler()"
    :x-on:click="$button->getAlpineClickHandler()"
    :disabled="$button->isDisabled()"
    :color="$button->getColor()"
    :size="$button->getSize()"
    :icon="$button->getIcon()"
    :icon-position="$button->getIconPosition()"
>
    {{ $button->getLabel() }}
</x-filament::button> 