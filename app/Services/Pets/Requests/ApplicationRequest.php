<?php

namespace App\Services\Pets\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

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
            'trial_date' => 'required_if:type,trial_day|date|after_or_equal:today',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->type === 'trial_day' && $this->trial_date) {
                $dayOfWeek = Carbon::parse($this->trial_date)->dayOfWeekIso;
                if (!in_array($dayOfWeek, [1, 2, 3, 4, 5])) {
                    $validator->errors()->add('trial_date', 'Trial day можна обрати лише у будні дні');
                }
            }
        });
    }
}