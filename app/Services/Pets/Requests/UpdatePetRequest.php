<?php

namespace App\Services\Pets\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'type' => 'sometimes|required|string',
            'breed_visual' => 'nullable|string',
            'name' => 'sometimes|required|string',
            'sex' => 'sometimes|required|string',
            'description' => 'sometimes|required|string',
            'age_months' => 'sometimes|required|integer|min:0',
            'size' => 'sometimes|required|string',
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