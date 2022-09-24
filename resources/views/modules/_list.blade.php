
@foreach ($rows as $item)

    <div class="bg-white border rounded-sm">
        <div class="p-2">
            {!! $popupEdit($item, $item->title) !!}
        </div>

        <div class="p-2 border-t">
            {{$item->description}}
        </div>

        <div class="flex justify-between p-2">
            <div>
                @if($item->installed)
                    <x-button danger small wire:click="$emit('uninstall','{{$item->code}}')">제거</x-button>
                @else
                    @if($item->ext == "zip")
                    <x-button primary small wire:click="$emit('install','{{$item->code}}')">설치</x-button>
                    @else
                    <x-button primary small wire:click="$emit('install','{{$item->code}}')">복제</x-button>
                    @endif
                @endif
            </div>
            <div>
                @if($item->installed)
                    @if($item->enable)
                        <x-button warning small wire:click="$emit('disable','{{$item->code}}')">Disable</x-button>
                    @else
                        <x-button success small wire:click="$emit('enable','{{$item->code}}')">Enable</x-button>
                    @endif

                    @if($item->ext == "zip")
                    <x-button primary small wire:click="$emit('install','{{$item->code}}')">Upgrade</x-button>
                    @else
                    <x-button primary small wire:click="$emit('install','{{$item->code}}')">Pull</x-button>
                    @endif
                @endif
            </div>
        </div>

    </div>
@endforeach

