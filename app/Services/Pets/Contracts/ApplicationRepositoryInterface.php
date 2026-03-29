<?php

namespace App\Services\Pets\Contracts;

use App\Services\Pets\Models\Application;

interface ApplicationRepositoryInterface
{
    public function create(array $data): Application;
    public function updateStatus(Application $application, string $status): bool;
}