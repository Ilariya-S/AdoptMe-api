<?php

namespace App\Services\Pets\Contracts;

use App\Services\Pets\Models\Pet;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
interface PetRepositoryInterface
{
    public function getAllPublic(int $perPage = 15): LengthAwarePaginator;
    public function findById(string $id): ?Pet;
    public function create(array $data): Pet;
    public function update(Pet $pet, array $data): bool;
    public function delete(Pet $pet): bool;
}