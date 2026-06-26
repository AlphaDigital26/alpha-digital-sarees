@props(['url'])
@php
    $settings = \App\Models\Setting::first();
@endphp
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
        
            @if (isset($message) && $message)
                <img src="{{ $message->embed(public_path('images/logo.png')) }}" class="logo" alt="ALPHA DIGITAL SAREES Logo" style="max-height: 60px; width: auto;">
            @else
                <img src="{{ asset('images/logo.png') }}" class="logo" alt="ALPHA DIGITAL SREES Logo" style="max-height: 60px; width: auto;">
            @endif

        </a>
    </td>
</tr>