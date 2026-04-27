<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'exists:users,email'],
            'otp' => ['required', 'numeric', 'digits:6'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'The email address is required to verify the OTP.',
            'email.exists' => 'No user was found with this email address.',
            'otp.required' => 'The OTP code is required.',
            'otp.numeric' => 'The OTP code must contain only numbers.',
            'otp.digits' => 'The OTP code must be exactly 6 digits.',
        ];
    }
}
