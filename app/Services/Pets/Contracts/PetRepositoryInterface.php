<?php

namespace App\Services\Pets\Contracts;

use App\Services\Pets\Models\Pet;
use Illuminate\Database\Eloquent\Collection;

interface PetRepositoryInterface
{
    public function getAllPublic(): Collection;
    public function findById(string $id): ?Pet;
    public function create(array $data): Pet;
    public function update(Pet $pet, array $data): bool;
    public function delete(Pet $pet): bool;
}