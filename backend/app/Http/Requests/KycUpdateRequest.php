<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KycUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id_number' => ['required', 'string', 'max:20'],
            'birth_year' => ['nullable', 'integer', 'min:1950', 'max:' . date('Y')],
            'address' => ['required', 'string', 'max:500'],
            'withdrawal_method' => ['required', 'in:mpesa,bank'],
            'mpesa_phone' => ['required_if:withdrawal_method,mpesa', 'nullable', 'string', 'max:15'],
            'bank_name' => ['required_if:withdrawal_method,bank', 'nullable', 'string', 'max:255'],
            'bank_account_number' => ['required_if:withdrawal_method,bank', 'nullable', 'string', 'max:50'],
            'bank_account_name' => ['required_if:withdrawal_method,bank', 'nullable', 'string', 'max:255'],
            'id_front' => ['nullable', 'image', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'id_back' => ['nullable', 'image', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'address_proof' => ['nullable', 'image', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_front.max' => 'ID front image must not exceed 5MB.',
            'id_back.max' => 'ID back image must not exceed 5MB.',
            'address_proof.max' => 'Address proof must not exceed 5MB.',
            'profile_photo.max' => 'Profile photo must not exceed 5MB.',
        ];
    }
}
