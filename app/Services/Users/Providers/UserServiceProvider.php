<?php

namespace App\Services\Users\Providers;

use App\Providers\BaseServiceProvider;
use App\Services\Users\Contracts\UserRepositoryInterface;
use App\Services\Users\Repositories\UserRepository;

class UserServiceProvider extends BaseServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadApiRoutes(__DIR__ . '/../Routes/routes.php');

    }
}
