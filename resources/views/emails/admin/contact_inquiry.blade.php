<x-mail::message>
# New Contact Inquiry

You have received a new message from the contact form.

**From:** {{ $query->name }} ({{ $query->email }})
**Phone:** {{ $query->phone ?? 'N/A' }}
**Reason:** {{ $query->reason ?? 'General Inquiry' }}

**Message:**
<x-mail::panel>
{{ $query->message }}
</x-mail::panel>

<x-mail::button :url="url('/admin/user-queries')">
View in Admin Panel
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
