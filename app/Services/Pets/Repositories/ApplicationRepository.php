<?php

namespace App\Services\Pets\Repositories;

use App\Services\Pets\Contracts\ApplicationRepositoryInterface;
use App\Services\Pets\Models\Application;

class ApplicationRepository implements ApplicationRepositoryInterface
{
    public function create(array $data): Application
    {
        return Application::create($data);
    }

    public function updateStatus(Application $application, string $status): bool
    {
        return $application->update(['status' => $status]);
    }
}