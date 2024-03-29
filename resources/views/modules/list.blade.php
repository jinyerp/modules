
@foreach ($rows as $item)
<div class="col-12 col-md-6 col-lg-3 mb-2">
    <div class="card h-100">

        @if($item->image)
        <img class="card-img-top" src="img/photos/unsplash-1.jpg" alt="Unsplash">
        @endif

        <div class="card-header px-4 pt-4">
            @if($item->installed)
            <div class="card-actions float-end">
                <div class="dropdown position-relative">
                    <a href="#" data-bs-toggle="dropdown" data-bs-display="static">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal align-middle"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        {{-- <a class="dropdown-item" href="#">Action</a> --}}

                        @if($item->ext == "zip")
                        <a class="dropdown-item" href="#" wire:click="$emit('install','{{$item->code}}')">Upgrade</a>
                        @else
                        <a class="dropdown-item" href="#" wire:click="$emit('install','{{$item->code}}')">Pull</a>
                        @endif

                        <a class="dropdown-item" href="#" wire:click="$emit('uninstall','{{$item->code}}')">제거</a>

                    </div>
                </div>
            </div>
            @endif

            <h5 class="card-title mb-0">{!! $popupEdit($item, $item->title) !!}</h5>

            @if($item->installed)
                @if($item->enable)
                <div class="badge bg-primary my-2 cursor-pointer" wire:click="$emit('disable','{{$item->code}}')">Enabled</div>
                @else
                <div class="badge bg-warning my-2 cursor-pointer" wire:click="$emit('enable','{{$item->code}}')">Disable</div>
                @endif
            @endif
        </div>
        <div class="card-body px-4 pt-2">
            <p>{{$item->description}}</p>

            @if($item->version)
            <div>
                Last Version : {{$item->version}}
            </div>
            @endif

        </div>
        {{--
        <ul class="list-group list-group-flush">
            <li class="list-group-item px-4 pb-4">
                <p class="mb-2 font-weight-bold">Progress <span class="float-end">65%</span></p>
                <div class="progress progress-sm">
                    <div class="progress-bar" role="progressbar" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100" style="width: 65%;">
                    </div>
                </div>
            </li>
        </ul>
        --}}
        <div class="card-footer px-4 pt-2">
            @if($item->installed)

            @else
                @if($item->ext == "zip")
                <x-button primary wire:click="$emit('install','{{$item->code}}')">설치</x-button>
                @else
                <button class="btn mb-1 btn-github w-full"
                wire:click="$emit('install','{{$item->code}}')"
                >
                    <div class="flex justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-github" viewBox="0 0 16 16">
                            <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.012 8.012 0 0 0 16 8c0-4.42-3.58-8-8-8z"/>
                        </svg>
                        <span class="ml-2">복제</span>
                    </div>
                </button>
                @endif
            @endif
        </div>

    </div>
</div>
@endforeach

