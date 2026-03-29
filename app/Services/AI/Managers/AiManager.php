<?php

namespace App\Services\AI\Managers;

use Illuminate\Support\Facades\Http;
use App\Services\Pets\Models\Pet;

class AiManager
{
    public function getChatResponse(array $userMessages): string
    {
        // 1. Дістаємо з бази тільки ДОСТУПНИХ тварин
        $availablePets = Pet::where('status', 'available')
            ->limit(10)
            ->get(['id', 'type', 'name', 'age_months', 'size', 'temperament_tags', 'monthly_cost'])
            ->toArray();

        $petsJson = json_encode($availablePets, JSON_UNESCAPED_UNICODE);

        // 2. Системний промпт від Іри
        $systemPrompt = "Ти — привітний AI-консультант притулку 'AdoptMe Dnipro'. 
        Твоя мета — допомогти людині підібрати тваринку.
        ПРАВИЛА:
        1. Відповідай ВИКЛЮЧНО українською мовою.
        2. Будь лаконічним, не пиши довгих текстів.
        3. Задавай питання по одному.
        4. Коли зрозумієш потреби, запропонуй тваринку ТІЛЬКИ з цього списку: {$petsJson}. 
        5. Якщо список порожній, скажи, що зараз вільних тваринок немає.
        6. Обов'язково згадуй про витрати на місяць (monthly_cost).";

        $messagesForApi = [
            ['role' => 'system', 'content' => $systemPrompt]
        ];

        $messagesForApi = array_merge($messagesForApi, $userMessages);

        $response = Http::withToken(env('GROQ_API_KEY'))
            ->timeout(10)
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.1-8b-instant',
                'messages' => $messagesForApi,
                'temperature' => 0.2,
            ]);

        if ($response->failed()) {
            info('Groq Error: ' . $response->body());
            return "Вибачте, я зараз трохи перевантажений. Спробуйте написати ще раз!";
        }

        return $response->json('choices.0.message.content');
    }
}