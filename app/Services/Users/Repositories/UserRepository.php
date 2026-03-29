<?php

namespace App\Services\Users\Repositories;

use App\Services\Users\Contracts\UserRepositoryInterface;
use App\Services\Users\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }
}