@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'AgriAlert')
<img src="{{ asset('assets/logo.jpg') }}" class="logo" alt="AgriAlert Logo">
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
