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
        Твоя мета — підібрати ідеальну тваринку для користувача.

        ОСЬ ТВІЙ СУВОРИЙ АЛГОРИТМ ДІЙ:
        1. Проаналізуй попередні повідомлення. Рахуй, скільки питань ти вже задав.
        2. Якщо ти ще не знаєш умов життя (квартира/будинок), графіка роботи та бюджету — задай уточнююче питання.
        3. ВАЖЛИВО: Задавай ТІЛЬКИ ОДНЕ питання за одне повідомлення. Не вивалюй список питань.
        4. Загалом ти маєш задати максимум 2-3 питання за весь діалог.
        5. КОЛІ ТИ ЗІБРАВ ІНФОРМАЦІЮ (після 2-3 питань): більше нічого не питай! Одразу пропонуй 1-2 тваринки ТІЛЬКИ з цього списку: {$petsJson}.
        6. Коли пропонуєш тваринку, обов'язково вкажи її ім'я, вік, розмір та очікувані витрати на місяць (monthly_cost).
        7. Якщо підходящої тварини в списку немає, або список порожній, ввічливо скажи про це.
        8. Відповідай лаконічно, емпатійно та ВИКЛЮЧНО українською мовою.";

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