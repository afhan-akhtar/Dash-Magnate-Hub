@php
$i = 1;
$Content = 'Region';
@endphp
@forelse($region as $r)
<tr>
    <td>{{ $i }}</td>
    <td>{{ $r->name }}</td>
    <td>{{ $r->location_name ?? '-' }}</td>
    <td class="text-center">
        <button title="Delete {{ $Content }}" type="button" class="btn btn-outline-danger waves-effect waves-light m-1 Table_Button Delete_Button" data-id="{{ $r->id }}" data-toggle="modal" data-target="#Delete_Model">
            <i class="fa fa fa-trash-o fa-2x"></i>
        </button>
        <a href="/dashboard/region/{{ $r->id }}/edit">
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
<tr><td colspan="4" class="text-center">Nothing Found</td></tr>
@endforelse

<script>
    $(".Delete_Button").on('click', function() { $("input.region-id").val($(this).data('id')); });
</script>
