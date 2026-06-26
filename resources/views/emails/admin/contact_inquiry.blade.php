<x-mail::message>

# New Contact Inquiry 📬

Hi **Admin**,

You have received a new message from the contact form.

### Sender Details
**From:** {{ $query->name }}<br>
**Email:** {{ $query->email }}<br>
**Phone:** {{ $query->phone ?? 'N/A' }}<br>
**Reason:** {{ $query->reason ?? 'General Inquiry' }}

### Message

<x-mail::panel>
{{ $query->message }}
</x-mail::panel>

<x-mail::button :url="url('/admin/user-queries')" color="primary">
View in Admin Panel
</x-mail::button>

Warm regards,<br>
**The ALPHA DIGITAL SAREES System**
</x-mail::message>
