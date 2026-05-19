@php
$i=1;
$Content = 'User';
@endphp
@forelse($user as $u)
<tr>
    <td>{{$i}}</td>
    <td>
        @if($u->profile != null)
            <img src="{{ asset('/uploads/user/profile/' . $u->profile) }}" alt="{{ $Content }} Profile">
        @else   
            <img src="/user/assets/profile.png" alt="{{ $Content }} Profile">
        @endif
    </td>
    <td>{{$u->name}}</td>
    <td>{{$u->email}}</td>
    <td>{{$u->phone}}</td>
    <td>
        @if ($u->status == 0)
            <span class="badge badge-success shadow-success m-1">Active</span>
        @else
            <span class="badge badge-danger shadow-danger m-1">De-Active</span>
        @endif
    </td>
    <td>{{$u->date}}</td>
    <td class="text-center">
        <button title="Delete {{ $Content }}" type="button" class="btn btn-outline-danger waves-effect waves-light m-1 Table_Button" Code="{{ $u->code }}" data-toggle="modal" data-target="#Delete_Model">
            <i class="fa fa fa-trash-o fa-2x"></i>
        </button>

        @if ($u->status == 1)
            <button title="Active {{ $Content }}" type="button" class="btn btn-outline-success waves-effect waves-light m-1 Table_Button Status" Code="{{ $u->code }}">
                <i class="fa fa-unlock fa-2x"></i>
            </button>
        @else
            <button title="De-Active {{ $Content }}" type="button" class="btn btn-outline-danger waves-effect waves-light m-1 Table_Button Status" Code="{{ $u->code }}">
                <i class="fa fa-lock fa-2x"></i>
            </button>
        @endif
    </td>
</tr>
@php
$i++;
@endphp
@empty
<td colspan="8" class="text-center">Nothing Found</td>
@endforelse

