<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CancelOrder implements ValidationRule
{
    public function __construct(protected string $orderStatus) {}
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $allowedStatuses = ['pending', 'preparing'];

        if ($value === 'cancelled' && !in_array($this->orderStatus, $allowedStatuses)) {
            $fail('You can only cancel your order while it is in Pending or Preparing.');
        }
    }
}
