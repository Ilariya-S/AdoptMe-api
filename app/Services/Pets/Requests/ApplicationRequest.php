<?php

namespace App\Services\Pets\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pet_id' => 'required|integer|exists:pets,id',
            'type' => 'required|string|in:trial_day,adoption',
        ];
    }
}