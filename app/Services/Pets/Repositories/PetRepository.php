<?php

namespace App\Services\Pets\Repositories;

use App\Services\Pets\Contracts\PetRepositoryInterface;
use App\Services\Pets\Models\Pet;
use Illuminate\Database\Eloquent\Collection;

class PetRepository implements PetRepositoryInterface
{
    public function getAllPublic(): Collection
    {
        return Pet::select(['id', 'name', 'photo_url', 'type', 'breed_visual', 'status', 'age_months'])
            ->get();
    }

    public function findById(string $id): ?Pet
    {
        return Pet::find($id);
    }

    public function create(array $data): Pet
    {
        return Pet::create($data);
    }

    public function update(Pet $pet, array $data): bool
    {
        return $pet->update($data);
    }

    public function delete(Pet $pet): bool
    {
        return $pet->delete();
    }
}