@php
$i=1;
$Content = 'Account';
@endphp
@forelse($Account as $a)
<tr>
    <td>{{$i}}</td>
    <td>{{$a->name}}</td>
    <td>
        @if ($a->status == 0)
            <span class="badge badge-success shadow-success m-1">Active</span>
        @else
            <span class="badge badge-danger shadow-danger m-1">De-Active</span>
        @endif
    </td>
    <td>{{$a->date}}</td>
    <td class="text-center">
        <button title="Delete {{ $Content }}" type="button" class="btn btn-outline-danger waves-effect waves-light m-1 Table_Button" Code="{{ $a->code }}" data-toggle="modal" data-target="#Delete_Model">
            <i class="fa fa fa-trash-o fa-2x"></i>
        </button>

        @if ($a->status == 1)
            <button title="Active {{ $Content }}" type="button" class="btn btn-outline-success waves-effect waves-light m-1 Table_Button Status" Code="{{ $a->code }}">
                <i class="fa fa-unlock fa-2x"></i>
            </button>
        @else
            <button title="De-Active {{ $Content }}" type="button" class="btn btn-outline-danger waves-effect waves-light m-1 Table_Button Status" Code="{{ $a->code }}">
                <i class="fa fa-lock fa-2x"></i>
            </button>
        @endif

        <a href="/dashboard/account/{{$a->code}}/edit">
            <button title="Edit {{ $Content }}" type="button" class="btn btn-outline-success waves-effect waves-light m-1 Table_Button">
                <i class="fa fa-edit fa-2x"></i>
            </button>
        </a>
    </td>
</tr>
@php
$i++;
@endphp
@empty
<td colspan="6" class="text-center">Nothing Found</td>
@endforelse


