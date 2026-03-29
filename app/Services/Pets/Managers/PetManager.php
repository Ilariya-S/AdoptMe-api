<?php

namespace App\Services\Pets\Managers;

use App\Services\Pets\Contracts\PetRepositoryInterface;
use App\Services\Pets\Models\Pet;

class PetManager
{
    public function __construct(protected PetRepositoryInterface $petRepo)
    {
    }

    public function getCatalog()
    {
        return $this->petRepo->getAllPublic();
    }

    public function getDetails(string $id)
    {
        return $this->petRepo->findById($id);
    }

    public function storePet(array $data)
    {
        return $this->petRepo->create($data);
    }

    public function updatePet(Pet $pet, array $data)
    {
        $this->petRepo->update($pet, $data);
        return $pet->refresh();
    }

    public function deletePet(Pet $pet)
    {
        return $this->petRepo->delete($pet);
    }
}