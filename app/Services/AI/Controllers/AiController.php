<?php

namespace App\Services\AI\Controllers;

use App\Http\Controllers\Controller;
use App\Services\AI\Requests\ChatRequest;
use App\Services\AI\Managers\AiManager;
use Illuminate\Http\JsonResponse;

class AiController extends Controller
{
    public function __construct(protected AiManager $aiManager)
    {
    }

    public function chat(ChatRequest $request): JsonResponse
    {
        $messages = $request->validated()['messages'];

        $reply = $this->aiManager->getChatResponse($messages);

        return response()->json([
            'reply' => $reply
        ]);
    }
}