<?php

namespace App\Services\Pets\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'type' => 'required|string',
            'breed_visual' => 'nullable|string',
            'name' => 'required|string',
            'sex' => 'required|string',
            'description' => 'required|string',
            'age_months' => 'required|integer|min:0',
            'size' => 'required|string',
            'weight_kg' => 'nullable|numeric|min:0',
            'color' => 'nullable|string',
            'sterilized' => 'nullable|boolean',
            'temperament_tags' => 'nullable|array',
            'health_status' => 'nullable|string',
            'medical_conditions' => 'nullable|string',
            'ideal_owner_tags' => 'nullable|array',
            'photo_url' => 'nullable|string',
            'monthly_cost' => 'nullable|integer|min:0',
        ];
    }
}