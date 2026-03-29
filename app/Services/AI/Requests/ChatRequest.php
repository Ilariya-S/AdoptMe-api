<?php

namespace App\Services\AI\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Чат доступний всім
    }

    public function rules(): array
    {
        return [
            // Перевіряємо, що нам точно передають масив повідомлень
            'messages' => 'required|array',
            'messages.*.role' => 'required|string|in:user,assistant',
            'messages.*.content' => 'required|string',
        ];
    }
}