<?php

namespace App\Services\Pets\Managers;

use App\Services\Pets\Repositories\ApplicationRepository;
use App\Services\Pets\Models\Application;
use App\Services\Pets\Models\Pet;
use Illuminate\Support\Facades\DB;

class ApplicationManager
{
    public function __construct(protected ApplicationRepository $appRepo)
    {
    }

    public function submitApplication(array $data, int $userId)
    {
        $pet = Pet::find($data['pet_id']);
        if ($pet->status === 'adopted') {
            abort(400, 'Ця тваринка вже знайшла свій дім!');
        }

        $existingApp = Application::where('user_id', $userId)
            ->where('pet_id', $data['pet_id'])
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($existingApp) {
            abort(400, 'Ви вже маєте активну заявку на цю тваринку.');
        }

        $data['user_id'] = $userId;
        $data['status'] = 'pending';

        return $this->appRepo->create($data);
    }

    public function approveApplication(Application $application)
    {
        DB::transaction(function () use ($application) {
            $this->appRepo->updateStatus($application, 'approved');
            $newPetStatus = $application->type === 'trial_day' ? 'trial' : 'adopted';
            $application->pet()->update(['status' => $newPetStatus]);
        });

        return $application->refresh();
    }
    public function rejectApplication(Application $application)
    {
        DB::transaction(function () use ($application) {
            $this->appRepo->updateStatus($application, 'rejected');

            $application->pet()->update(['status' => 'available']);
        });

        return $application->refresh();
    }
}