@props(['url'])
@php
    $settings = \App\Models\Setting::first();
@endphp
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block; text-decoration: none;">
@if ($settings && $settings->logo_type === 'image' && $settings->logo_image)
<img src="{{ asset('storage/' . $settings->logo_image) }}" class="logo" alt="{{ $settings->logo_text ?? config('app.name') }}" style="max-height: 50px; width: auto;">
@else
<span style="font-size: 19px; font-weight: bold; color: #3d4852; text-decoration: none;">
    {{ $settings->logo_text ?? config('app.name') }}
</span>
@endif
</a>
</td>
</tr>
