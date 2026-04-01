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
            ->limit(15)
            ->get(['id', 'type', 'name', 'age_months', 'size', 'temperament_tags', 'monthly_cost'])
            ->toArray();

        $petsJson = json_encode($availablePets, JSON_UNESCAPED_UNICODE);

        // 2. Системний промпт від Іри
        $systemPrompt = "Ти — емпатійний AI-експерт платформи 'AdoptMe Dnipro'. 
            Твоя місія: допомогти людині знайти вірного друга серед мешканців нашого притулку.

            ОСЬ ТВОЯ БАЗА ТВАРИН (JSON):
            {$petsJson}

            ТВІЙ АЛГОРИТМ (КРОК ЗА КРОКОМ):

            1. ПЕРЕВІРКА КОНТЕКСТУ: Проаналізуй історію чату. Якщо це перше повідомлення — привітайся коротко і запитай, кого саме шукає користувач (котика чи собаку).

            2. СТРАТЕГІЯ ОПИТУВАННЯ (СУВОРО): 
            - Задавай ТІЛЬКИ ОДНЕ ПИТАННЯ за раз.
            - Тобі потрібно дізнатися 3 ключові речі: 
                а) Умови життя (квартира чи будинок);
                б) Досвід та графік (скільки часу людина буде вдома);
                в) Бюджет (чи готова людина до витрат, зазначених у monthly_cost).
            - Якщо користувач уже дав відповідь на щось у своєму повідомленні — не перепитуй, переходь до наступного пункту.

            3. ЛІМІТ: Після того, як ти отримав відповіді на ці 3 пункти (або пройшло 3 ходи діалогу), ПРИПИНЯЙ питати. 

            4. ПІДБІР ТА РЕКОМЕНДАЦІЯ:
            - Порівняй дані користувача з 'size', 'temperament_tags' та 'monthly_cost' у списку тварин.
            - Запропонуй 1-2 найбільш підходящі варіанти.
            - Для кожної тваринки обов'язково напиши:
                * Ім'я та вік (переводь місяці у роки, якщо > 12).
                * Чому саме вона підходить (на основі тегів).
                * Орієнтовні витрати: [monthly_cost] грн/міс.
            - Якщо ідеального збігу немає — запропонуй найбільш близького за характером.

            5. ТОН ТА МОВА:
            - Мова: Українська.
            - Тон: Теплий, професійний, лаконічний. 
            - Уникай довгих вступів. Кожне повідомлення має бути коротким (до 3-4 речень).

            ВАЖЛИВО: Якщо в JSON-списку порожньо, скажи, що зараз усі хвостики знайшли дім, але скоро будуть нові. ";


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