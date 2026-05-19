@php
$i=1;
$Content = 'Blog';
@endphp
@forelse($blog as $b)
<tr>
    <td>{{$i}}</td>
    <td><img src="{{ asset('/uploads/blog/card/' . $b->card) }}" alt="{{ $Content }} Card Image"></td>
    <td>{{$b->name}}</td>
    <td>{{$b->writter_name}}</td>
    <td>
        @if ($b->active == 1)
            <span class="badge badge-success shadow-success m-1">Active</span>
        @else
            <span class="badge badge-danger shadow-danger m-1">De-Active</span>
        @endif
    </td>
    <td>{{$b->account}}</td>
    <td>{{$b->date}}</td>
    <td class="text-center">
        <button title="Delete {{ $Content }}" type="button" class="btn btn-outline-danger waves-effect waves-light m-1 Table_Button" Code="{{ $b->code }}" data-toggle="modal" data-target="#Delete_Model">
            <i class="fa fa fa-trash-o fa-2x"></i>
        </button>

        @if ($b->active == 0)
            <button title="Active {{ $Content }}" type="button" class="btn btn-outline-success waves-effect waves-light m-1 Table_Button Status" Code="{{ $b->code }}">
                <i class="fa fa-unlock fa-2x"></i>
            </button>
        @else
            <button title="De-Active {{ $Content }}" type="button" class="btn btn-outline-danger waves-effect waves-light m-1 Table_Button Status" Code="{{ $b->code }}">
                <i class="fa fa-lock fa-2x"></i>
            </button>
        @endif

        <a href="/dashboard/blog/{{$b->code}}/edit">
            <button title="Edit {{ $Content }}" type="button" class="btn btn-outline-success waves-effect waves-light m-1 Table_Button">
                <i class="fa fa-edit fa-2x"></i>
            </button>
        </a>

        <!-- <a href="/dashboard/blog/{{$b->code}}/write">
            <button title="Write {{ $Content }}" type="button" class="btn btn-outline-primary waves-effect waves-light m-1 Table_Button">
                <i class="fa fa-pencil fa-2x"></i>
            </button>
        </a> -->
    </td>
</tr>
@php
$i++;
@endphp
@empty
<td colspan="8" class="text-center">Nothing Found</td>
@endforelse

<script>
	$(".Table_Button").on('click', function(){ $(".code").val($(this).attr('Code')); });
    $(".Status").on('click', function(e){ e.preventDefault(); var fd  = new FormData();
    fd.append('code', $(this).attr('Code')); fd.append('_token', $("input[name=_token]").val());
    $.ajax({method: "POST",url:'/<?php echo $Content ?>/Status',processData: false,contentType: false,data: fd,})
    .done(function(response) {if (response.error == 'logout') {location.assign("/dashboard/logout");}
    if (response.error == false) {Get(); Notification('success','mini','fa fa-check','bottom right',response.message);}});});
</script>

