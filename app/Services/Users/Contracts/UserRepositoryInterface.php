<?php

namespace App\Services\Users\Contracts;

use App\Services\Users\Models\User;

interface UserRepositoryInterface
{
    public function create(array $data): User;
    public function findByEmail(string $email): ?User;
}