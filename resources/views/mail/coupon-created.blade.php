<x-mail::message>
# 🎉 You've Received a Coupon!

Great news!

You’ve just been given a **coupon** that you can now use on your next purchase.

@if(isset($coupon->code))
**Your Coupon Code:**  
`{{ $coupon->code }}`
@endif

@if(isset($coupon->discount_percentage))
**Discount:** %{{ $coupon->discount_percentage }}
@endif

@if(isset($coupon->expires_at))
**Expires On:** {{ $coupon->expires_at->format('F j, Y g:i A') }}
@endif

@if(isset($coupon->max_applicable_price))
**Max Applicable Total Price On Your Cart:** ${{ $coupon->max_applicable_price }}
@endif

<x-mail::button :url="config('app.site_base_url')">
Start Shopping
</x-mail::button>

Make sure to apply your coupon at checkout to enjoy the discount.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>