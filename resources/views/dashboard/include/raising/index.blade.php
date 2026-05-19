@php
$i=1;
$Content = 'Raising';
@endphp
@forelse($raising as $u)
<tr>
    <td>{{$i}}</td>
    <td>
        @if($u->profile != null)
            <img src="{{ asset('/uploads/raising/profile/' . $u->profile) }}" alt="{{ $Content }} Profile">
        @else   
            <img src="/user/assets/profile.png" alt="{{ $Content }} Profile">
        @endif
    </td>
    <td>{{$u->first_name}} {{$u->last_name}}</td>
    <td>{{$u->email}}</td>
    <td>{{$u->phone}}</td>
    <td>
        @if ($u->type == 1)
            <span class="badge badge-info shadow-info m-1">Buyer</span>
        @elseif ($u->type == 2)
            <span class="badge badge-warning shadow-warning m-1">Seller</span>
        @elseif ($u->type == 3)
            <span class="badge badge-primary shadow-primary m-1">Raiser/Broker</span>
        @else
            <span class="badge badge-secondary shadow-secondary m-1">Not Set</span>
        @endif
    </td>
    <td>
        @if ($u->plan_type !== null)
            @if ($u->plan_type == 0)
                <span class="badge badge-secondary shadow-secondary m-1">No Plan</span>
            @elseif ($u->plan_type == 1)
                <span class="badge badge-warning shadow-warning m-1" style="background: #f39c12;">Basic</span>
            @elseif ($u->plan_type == 2)
                <span class="badge badge-info shadow-info m-1" style="background: #3498db;">Standard</span>
            @elseif ($u->plan_type == 3)
                <span class="badge badge-success shadow-success m-1" style="background: #2ecc71;">Premium</span>
            @elseif ($u->plan_type == 4)
                <span class="badge badge-primary shadow-primary m-1" style="background: #9b59b6;">Enterprise</span>
            @elseif ($u->plan_type == 5)
                <span class="badge badge-success shadow-success m-1" style="background: #27ae60;">Free (3 Months)</span>
            @else
                <span class="badge badge-dark shadow-dark m-1">Plan {{ $u->plan_type }}</span>
            @endif
            @if ($u->plan_expiry)
                <br><small style="font-size: 10px; color: #6c757d;">Expires: {{ date('d M Y', strtotime($u->plan_expiry)) }}</small>
            @endif
        @else
            <span class="badge badge-secondary shadow-secondary m-1">No Active Plan</span>
        @endif
    </td>
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

        <a href="/dashboard/raiser/{{$u->code}}/info">
            <button title="Raiser Info" type="button" class="btn btn-outline-primary waves-effect waves-light m-1 Table_Button">
                <i class="fa fa-info fa-2x"></i>
            </button>
        </a>

    </td>
</tr>
@php
$i++;
@endphp
@empty
<td colspan="10" class="text-center">Nothing Found</td>
@endforelse
