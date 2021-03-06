<x-datatable>
    <x-data-table-thead>
        <th width='50'>Id</th>
        <th width='150'>코드</th>
        <th width='200'> {!! xWireLink('모듈명', "orderBy('title')") !!}</th>
        <th>설명</th>
    </x-data-table-thead>

    @if(!empty($rows))
    <tbody>
        @foreach ($rows as $item)
        <x-data-table-tr :item="$item" :selected="$selected">
            <td width='50'>{{$item->id}}</td>
            <td width='150'>{{$item->code}}</td>
            <td width='200'>
                {!! $popupEdit($item, $item->title) !!}
            </td>
            <td >{{$item->description}}</td>
        </x-data-table-tr>
        @endforeach

    </tbody>
    @endif
</x-datatable>


@if(empty($rows))
<div>
    목록이 없습니다.
</div>
@endif


