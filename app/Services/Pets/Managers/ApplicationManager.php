<?php

namespace App\Services\Pets\Managers;

use App\Services\Pets\Repositories\ApplicationRepository;
use App\Services\Pets\Models\Application;
use Illuminate\Support\Facades\DB;

class ApplicationManager
{
    public function __construct(protected ApplicationRepository $appRepo)
    {
    }

    public function submitApplication(array $data, int $userId)
    {
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
        $this->appRepo->updateStatus($application, 'rejected');

        return $application->refresh();
    }
}