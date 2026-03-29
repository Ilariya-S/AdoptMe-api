<?php

namespace App\Services\Pets\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Pets\Managers\PetManager;
use App\Services\Pets\Requests\PetRequest;
use Illuminate\Http\JsonResponse;

class PetController extends Controller
{
    public function __construct(protected PetManager $petManager)
    {
    }

    public function index(): JsonResponse
    {
        return response()->json($this->petManager->getCatalog());
    }

    public function show(string $id): JsonResponse
    {
        $pet = $this->petManager->getDetails($id);
        if (!$pet)
            return response()->json(['message' => 'Тваринку не знайдено'], 404);

        return response()->json($pet);
    }

    public function store(PetRequest $request): JsonResponse
    {
        $pet = $this->petManager->storePet($request->validated());
        return response()->json($pet, 201);
    }

    public function update(PetRequest $request, string $id): JsonResponse
    {
        $pet = $this->petManager->getDetails($id);
        $updatedPet = $this->petManager->updatePet($pet, $request->validated());
        return response()->json($updatedPet);
    }

    public function destroy(string $id): JsonResponse
    {
        $pet = $this->petManager->getDetails($id);
        $this->petManager->deletePet($pet);
        return response()->json(['message' => 'Тваринку видалено']);
    }
}