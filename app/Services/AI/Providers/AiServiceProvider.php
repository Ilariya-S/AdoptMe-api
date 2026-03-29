<?php

namespace App\Services\AI\Providers;

use App\Providers\BaseServiceProvider;

class AiServiceProvider extends BaseServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadApiRoutes(__DIR__ . '/../Routes/routes.php');

    }
}
