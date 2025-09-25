<?php

return [
    'tenant_model' => App\Models\Tenant::class,
    'id_generator' => Stancl\Tenancy\UUIDGenerator::class,
    'central_domains' => [
        env('CENTRAL_DOMAIN', 'localhost'),
    ],
    'middleware' => [
        'initialize_tenancy_by_domain' => Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class,
        'prevent_access_from_central_domains' => Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains::class,
    ],
];
