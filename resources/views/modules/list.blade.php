
@foreach ($rows as $item)

    <div class="bg-white border rounded-sm">
        <div class="p-2">
            {!! $popupEdit($item, $item->title) !!}
        </div>

        <div class="p-2 border-t">
            {{$item->description}}
        </div>

        <div class="flex justify-end p-2 border-t">
            @if($item->installed)
                <div>
                @if($item->enable)
                    <x-button warning small wire:click="$emit('disable','{{$item->code}}')">Disable</x-button>
                @else
                    <x-button success small wire:click="$emit('enable','{{$item->code}}')">Enable</x-button>
                @endif
                <!-- uninstall -->
                <x-button danger small wire:click="$emit('uninstall','{{$item->code}}')">uninstall</x-button>
                </div>
            @else
                <!-- install -->
                <div>
                    <x-button primary small wire:click="$emit('install','{{$item->code}}')">install</x-button>
                </div>

            @endif
        </div>

    </div>
@endforeach

