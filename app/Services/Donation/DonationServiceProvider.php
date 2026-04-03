<?php

namespace App\Services\Donation;


use App\Providers\BaseServiceProvider;

class DonationServiceProvider extends BaseServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadApiRoutes(__DIR__ . '/../Routes/routes.php');

    }
}
