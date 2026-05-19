
@php
$i=1;
$Content = 'Project';
@endphp
@forelse($project as $p)
<tr>
    <td>{{$i}}</td>
    <td><strong style="color: #007bff;">#{{$p->project_id}}</strong></td>
    <td><img src="{{ asset('/uploads/project/card/' . $p->card) }}" alt="{{ $Content }} Card Image"></td>
    <td>{{$p->name}}</td>
    <td>{{$p->views}}</td>
    <td><a href="/dashboard/raiser/{{ $p->raising_code }}/info">{{$p->first_name}} {{$p->last_name}}</a></td>
    <td>
        @if ($p->status == 0)
            <span class="badge badge-success shadow-success m-1">Active</span>
        @else
            <span class="badge badge-danger shadow-danger m-1">De-Active</span>
        @endif

        @if ($p->premium == 0)
            <span class="badge badge-primary shadow-primary m-1">Normal</span>
        @else
            <span class="badge badge-warning shadow-warning m-1">Premium</span>
        @endif

        @if ($p->block == 1)
            <span class="badge badge-danger shadow-danger m-1">Block</span>
        @endif
    </td>
    {{-- <td>{{$p->account}}</td> --}}
    <td>{{$p->date}}</td>
    <td class="text-center">
        @if($p->block == 0)
            <a href="/dashboard/project/{{$p->code}}/edit">
                <button title="Edit {{ $Content }}" type="button" class="btn btn-outline-success waves-effect waves-light m-1 Table_Button">
                    <i class="fa fa-edit fa-2x"></i>
                </button>
            </a>
            <button title="Delete {{ $Content }}" type="button" class="btn btn-outline-danger waves-effect waves-light m-1 Table_Button" Code="{{ $p->code }}" data-toggle="modal" data-target="#Delete_Model">
                <i class="fa fa fa-trash-o fa-2x"></i>
            </button>

            @if ($p->status == 1)
                <button title="Active {{ $Content }}" type="button" class="btn btn-outline-success waves-effect waves-light m-1 Table_Button Status" Code="{{ $p->code }}">
                    <i class="fa fa-unlock fa-2x"></i>
                </button>
            @else
                <button title="De-Active {{ $Content }}" type="button" class="btn btn-outline-danger waves-effect waves-light m-1 Table_Button Status" Code="{{ $p->code }}">
                    <i class="fa fa-lock fa-2x"></i>
                </button>
            @endif

            

            <a href="/dashboard/project/{{$p->code}}/images">
                <button title="Images {{ $Content }}" type="button" class="btn btn-outline-primary waves-effect waves-light m-1 Table_Button">
                    <i class="fa fa-image fa-2x"></i>
                </button>
            </a>

            <a href="https://magnatehub.au/detail?url={{$p->url}}&id={{$p->project_id}}&category=undefined" target="_blank">
                <button title="View on Website" type="button" class="btn btn-outline-info waves-effect waves-light m-1 Table_Button">
                    <i class="fa fa-external-link fa-2x"></i>
                </button>
            </a>

            <button title="Ban {{ $Content }}" type="button" class="btn btn-outline-danger waves-effect waves-light m-1 Table_Button Ban" Code="{{ $p->code }}">
                <i class="fa fa-ban fa-2x"></i>
            </button>
        @else
            <button title="Un Ban {{ $Content }}" type="button" class="btn btn-outline-success waves-effect waves-light m-1 Table_Button Ban" Code="{{ $p->code }}">
                <i class="fa fa-refresh fa-2x"></i>
            </button>
        @endif
    </td>
</tr>
@php
$i++;
@endphp
@empty
<td colspan="9" class="text-center">Nothing Found</td>
@endforelse

<script>
	$(".Table_Button").on('click', function(){ $(".code").val($(this).attr('Code')); });
    $(".Status").on('click', function(e){ e.preventDefault(); var fd  = new FormData();
    fd.append('code', $(this).attr('Code')); fd.append('_token', $("input[name=_token]").val());
    $.ajax({method: "POST",url:'/<?php echo $Content ?>/Status',processData: false,contentType: false,data: fd,})
    .done(function(response) {if (response.error == 'logout') {location.assign("/dashboard/logout");}
    if (response.error == false) {Get(); Notification('success','mini','fa fa-check','bottom right',response.message);}});});

    $(".Ban").on('click', function(e){ e.preventDefault(); var fd  = new FormData();
    fd.append('code', $(this).attr('Code')); fd.append('_token', $("input[name=_token]").val());
    $.ajax({method: "POST",url:'/<?php echo $Content ?>/Ban',processData: false,contentType: false,data: fd,})
    .done(function(response) {if (response.error == 'logout') {location.assign("/dashboard/logout");}
    if (response.error == false) {Get(); Notification('success','mini','fa fa-check','bottom right',response.message);}});});
</script>

