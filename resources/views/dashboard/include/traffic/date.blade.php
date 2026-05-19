@php
$i = 1;
@endphp
@forelse($Traffic as $t)
<tr>
    <td>{{ $i }}</td>
    <td>{{ $t->country }}</td>
    <td>{{ $t->city }}</td>
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
