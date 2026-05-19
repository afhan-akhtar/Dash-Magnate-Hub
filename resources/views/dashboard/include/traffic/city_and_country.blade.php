@php
$i = 1;
@endphp
@forelse($Traffic as $t)
<tr>
    <td>{{ $i }}</td>
    <td>{{ date('F d Y', strtotime($t->date)) }} - {{ date('l', strtotime($t->date)) }}</td>
    <td>{{ $t->count }}</td>
</tr>
@php
$i++;
@endphp
@empty
<tr class="text-center">
    <td colspan="4">Nothing Found</td>
</tr>
@endforelse
