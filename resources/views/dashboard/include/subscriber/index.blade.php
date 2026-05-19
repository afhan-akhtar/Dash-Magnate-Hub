@php
$i=1;
$Content = 'User';
@endphp
@forelse($subscriber as $s)
<tr>
    <td>{{$i}}</td>
    <td>{{$s->email}}</td>
    <td>{{$s->date}}</td>
    <td class="text-center">
        <button title="Delete {{ $Content }}" type="button" class="btn btn-outline-danger waves-effect waves-light m-1 Table_Button" Code="{{ $s->code }}" data-toggle="modal" data-target="#Delete_Model">
            <i class="fa fa fa-trash-o fa-2x"></i>
        </button>
    </td>
</tr>
@php
$i++;
@endphp
@empty
<td colspan="4" class="text-center">Nothing Found</td>
@endforelse
