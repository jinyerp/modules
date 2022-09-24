<div>
    <x-loading-indicator/>

    {{-- 필터를 적용시 filter.blade.php 를 읽어 옵니다. --}}
    @if (isset($actions['view_filter']))
        @includeIf($actions['view_filter'])
    @endif

    @if (session()->has('message'))
        <div class="alert alert-success">{{session('message')}}</div>
    @endif

    <div>

        {{-- body --}}
        <div class="grid grid-cols-4 gap-4 ">
            @if (isset($actions['view_list']))
                @includeIf($actions['view_list'])
            @endif
        </div>


    </div>


    {{-- 선택삭제 --}}
    @include("jinytable::livewire.popup.delete")

    {{-- 퍼미션 알람--}}
    @include("jinytable::error.popup.permit")

</div>
