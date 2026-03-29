<?php

namespace App\Services\Pets\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Pets\Managers\PetManager;
use App\Services\Pets\Requests\PetRequest;
use App\Services\Pets\Requests\UpdatePetRequest;
use Illuminate\Http\JsonResponse;
use App\Services\Pets\Models\Pet;
use Illuminate\Http\Request;

class PetController extends Controller
{
    public function __construct(protected PetManager $petManager)
    {
    }

    public function index(): JsonResponse
    {
        $pets = Pet::where('status', '!=', 'adopted')->get();
        return response()->json($pets);
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

    public function update(UpdatePetRequest $request, $id): JsonResponse
    {
        $pet = Pet::findOrFail($id);

        $hasActiveApps = $pet->applications()->whereIn('status', ['pending', 'approved'])->exists();
        if ($hasActiveApps) {
            return response()->json(['error' => 'Неможливо змінити тварину: на неї є активна або схвалена заявка.'], 400);
        }

        $pet->update($request->validated());
        return response()->json($pet);
    }

    public function destroy(Request $request, $id): JsonResponse
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['error' => 'Тільки адміністратор може видаляти тварин.'], 403);
        }

        $pet = Pet::findOrFail($id);

        $hasActiveApps = $pet->applications()->whereIn('status', ['pending', 'approved'])->exists();
        if ($hasActiveApps) {
            return response()->json(['error' => 'Неможливо видалити тварину: на неї є активна або схвалена заявка.'], 400);
        }

        $pet->delete();
        return response()->json(['message' => 'Тваринку успішно видалено.']);
    }
}