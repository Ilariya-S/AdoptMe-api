<?php

namespace App\Services\Pets\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Pets\Managers\ApplicationManager;
use App\Services\Pets\Requests\ApplicationRequest;
use App\Services\Pets\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ApplicationController extends Controller
{
    public function __construct(protected ApplicationManager $appManager)
    {
    }

    public function store(ApplicationRequest $request): JsonResponse
    {
        $application = $this->appManager->submitApplication(
            $request->validated(),
            $request->user()->id
        );
        return response()->json($application, 201);
    }

    public function approve(Request $request, $id): JsonResponse
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Доступ заборонено'], 403);
        }

        $application = Application::findOrFail($id);
        $approvedApp = $this->appManager->approveApplication($application);

        return response()->json($approvedApp);
    }

    public function reject(Request $request, $id): JsonResponse
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Доступ заборонено'], 403);
        }

        $application = Application::findOrFail($id);

        $rejectedApp = $this->appManager->rejectApplication($application);

        return response()->json($rejectedApp);
    }
    public function myApplications(Request $request): JsonResponse
    {
        $applications = Application::with('pet')
            ->where('user_id', $request->user()->id)
            ->get();

        return response()->json($applications);
    }

    public function index(Request $request): JsonResponse
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Доступ заборонено'], 403);
        }

        $applications = Application::with(['user', 'pet'])->get();
        return response()->json($applications);
    }
    public function destroy(Request $request, $id): JsonResponse
    {
        $application = Application::findOrFail($id);

        if ($application->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Ви не можете видалити чужу заявку.'], 403);
        }

        if ($application->status === 'approved') {
            return response()->json(['error' => 'Неможливо видалити вже схвалену заявку.'], 400);
        }

        $application->delete();
        return response()->json(['message' => 'Заявку успішно скасовано.']);
    }
}