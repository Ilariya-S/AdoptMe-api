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

            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'has_children' => 'required|boolean',
            'has_other_pets' => 'required|boolean',

            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required|date_format:H:i',

            'agreed_to_costs' => 'required|accepted',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->type === 'trial_day' && $this->booking_date) {
                $dayOfWeek = Carbon::parse($this->booking_date)->dayOfWeekIso;

                if (!in_array($dayOfWeek, [1, 2, 3, 4, 5])) {
                    $validator->errors()->add('booking_date', 'Trial day можна обрати лише у будні дні (Пн-Пт)');
                }
            }
        });
    }
}