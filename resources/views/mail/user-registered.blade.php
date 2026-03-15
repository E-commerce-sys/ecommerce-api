<x-mail::message>

# Email Verification

Hello {{ $user->full_name }},

Use the following **One-Time Password (OTP)** to verify your email address.

# {{ $user->otp }}

This code will expire in **5 minutes**.

If you did not request this verification, please ignore this email.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>