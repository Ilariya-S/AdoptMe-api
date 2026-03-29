<?php

namespace App\Services\Pets\Providers;
use App\Services\Pets\Contracts\PetRepositoryInterface;
use App\Services\Pets\Repositories\PetRepository;
use App\Services\Pets\Contracts\ApplicationRepositoryInterface;
use App\Services\Pets\Repositories\ApplicationRepository;

use App\Providers\BaseServiceProvider;

class PetServiceProvider extends BaseServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(PetRepositoryInterface::class, PetRepository::class);
        $this->app->bind(ApplicationRepositoryInterface::class, ApplicationRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadApiRoutes(__DIR__ . '/../Routes/routes.php');

    }
}
