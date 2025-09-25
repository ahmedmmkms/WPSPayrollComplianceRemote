<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Tenant;
use Database\Seeders\Tenant\TenantSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Stancl\Tenancy\Facades\Tenancy;

class CreateTenant extends Command
{
    protected $signature = 'tenants:create
        {--name= : Display name for the tenant/company}
        {--domain= : Primary domain (e.g. acme.example.com)}
        {--email= : Contact email for the tenant}
        {--skip-seed : Do not run tenant seeders after creation}';

    protected $description = 'Create a new tenant with default company metadata and optional seeders.';

    public function handle(): int
    {
        $name = $this->option('name') ?: $this->ask('Tenant name');
        $domain = $this->option('domain') ?: $this->ask('Primary domain (e.g. acme.example.com)');
        $email = $this->option('email') ?: $this->ask('Contact email');

        if (! $name || ! $domain) {
            $this->error('Name and domain are required.');
            return self::INVALID;
        }

        $domain = strtolower(trim($domain));

        $tenant = Tenant::create([
            'id' => (string) Str::uuid(),
            'data' => [
                'company_name' => $name,
                'contact_email' => $email,
            ],
        ]);

        $tenant->domains()->create([
            'domain' => $domain,
        ]);

        Tenancy::initialize($tenant);

        Company::firstOrCreate([
            'tenant_id' => $tenant->id,
        ], [
            'name' => $name,
            'trade_license' => null,
            'contact_email' => $email,
        ]);

        if (! $this->option('skip-seed')) {
            $seeder = app(TenantSeeder::class);
            $seeder->run();
        }

        Tenancy::end();

        $this->info("Tenant {$tenant->id} created with domain {$domain}.");

        return self::SUCCESS;
    }
}
