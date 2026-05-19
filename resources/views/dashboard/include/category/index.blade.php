@php
$i=1;
$Content = 'Category';
@endphp
@forelse($category as $c)
<tr>
    <td>{{$i}}</td>
    <td><img src="{{ asset('/uploads/category/card/' . $c->card) }}" alt="{{ $Content }} Card Image"></td>
    <td>{{ str_replace('_', ' ', $c->name) }}</td>
    <td>
        @if ($c->active == 1)
            <span class="badge badge-success">Active</span>
        @else
            <span class="badge badge-danger">De-Active</span>
        @endif
    </td>
    <td>{{$c->account}}</td>
    <td>{{$c->date}}</td>
    <td class="text-center">
        <button title="Delete {{ $Content }}" type="button" class="btn btn-outline-danger waves-effect waves-light m-1 Table_Button Delete_Button" Code="{{ $c->code }}" data-toggle="modal" data-target="#Delete_Model">
            <i class="fa fa-trash-o fa-2x"></i>
        </button>

        @if ($c->active == 0)
            <button title="Active {{ $Content }}" type="button" class="btn btn-outline-success waves-effect waves-light m-1 Table_Button Status" Code="{{ $c->code }}">
                <i class="fa fa-unlock fa-2x"></i>
            </button>
        @else
            <button title="De-Active {{ $Content }}" type="button" class="btn btn-outline-danger waves-effect waves-light m-1 Table_Button Status" Code="{{ $c->code }}">
                <i class="fa fa-lock fa-2x"></i>
            </button>
        @endif

        <a href="/dashboard/category/{{$c->code}}/edit">
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
<td colspan="7" class="text-center">Nothing Found</td>
@endforelse

<script>
	$(".Delete_Button").on('click', function(){ $(".code").val($(this).attr('Code')); });
    $(".Status").on('click', function(e){ e.preventDefault(); var fd  = new FormData();
    fd.append('code', $(this).attr('Code')); fd.append('_token', $("input[name=_token]").val());
    $.ajax({method: "POST",url:'/<?php echo $Content ?>/Status',processData: false,contentType: false,data: fd,})
    .done(function(response) {if (response.error == 'logout') {location.assign("/dashboard/logout");}
    if (response.error == false) {Get(); Notification('success','mini','fa fa-check','bottom right',response.message);}});});
</script>

