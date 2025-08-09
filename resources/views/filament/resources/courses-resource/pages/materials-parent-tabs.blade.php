<div class="flex items-center space-x-2 mb-4" style="margin: auto">
    <button
        type="button"
        @class([
    'px-3 py-1 text-sm font-medium rounded-md',
    'bg-primary-500 text-white shadow-sm' => ($activeParent === null),
    'text-gray-700 hover:text-gray-900 hover:bg-gray-100' => ($activeParent !== null),
    ])
    wire:click="setParentFilter(null)"
    >
    {{__('common.all')}}
    </button>

    @foreach($parents as $parent)
        <button
            type="button"
            @class([
        'px-3 py-1 text-sm font-medium rounded-md',
        'bg-primary-500 text-white shadow-sm' => ($activeParent == $parent->id),
        'text-gray-700 hover:text-gray-900 hover:bg-gray-100' => ($activeParent != $parent->id),
        ])
        wire:click="setParentFilter({{ $parent->id }})"
        >
        {{ $parent->title }}
        </button>
    @endforeach
</div>
