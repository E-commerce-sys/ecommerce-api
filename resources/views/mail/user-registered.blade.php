<x-mail::message>
# Welcome to {{ config('app.name') }}! 🎉

Hi {{ $user->first_name }},

Thank you for signing up for {{ config('app.name') }}. We're excited to have you join our community!
Before you start exploring and shopping on our platform, please take a moment to verify your email address. This helps us keep your account secure and ensures you can create orders. 
Simply click the button below to confirm your email address.

<x-mail::button :url="config('app.frontend_origin')">
Verify My Email
</x-mail::button>

If you didn’t create an account with {{ config('app.name') }}, you can safely ignore this email.

We’re happy to have you here and hope you enjoy your experience with us!

Thanks,<br>
The {{ config('app.name') }} Team
</x-mail::message>
