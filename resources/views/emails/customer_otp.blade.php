<x-mail::message>
# Verify Your Email Address

Hi {{ $customerName }},

Thank you for registering with **Alpha Digital**! To complete your registration and secure your account, please use the following One-Time Password (OTP):

<x-mail::panel>
<div style="text-align: center; font-size: 24px; font-weight: bold; letter-spacing: 4px; color: #800020;">
{{ $otp }}
</div>
</x-mail::panel>

This code is valid for the next 10 minutes. If you did not request this, you can safely ignore this email.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
