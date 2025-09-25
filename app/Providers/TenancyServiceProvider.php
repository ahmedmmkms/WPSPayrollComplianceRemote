<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Stancl\Tenancy\Bootstrappers\CacheBootstrapper;
use Stancl\Tenancy\Bootstrappers\QueueBootstrapper;
use Stancl\Tenancy\Bootstrappers\StorageBootstrapper;
use Stancl\Tenancy\Middleware;
use Stancl\Tenancy\Tenancy;

class TenancyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        Tenancy::macro('bootstrappers', function () {
            return [
                CacheBootstrapper::class,
                QueueBootstrapper::class,
                StorageBootstrapper::class,
            ];
        });
    }

    public function boot(): void
    {
        Tenancy::routes()
            ->middleware([
                Middleware\InitializeTenancyByDomain::class,
                Middleware\PreventAccessFromCentralDomains::class,
            ])
            ->group(base_path('routes/tenant.php'));
    }
}
