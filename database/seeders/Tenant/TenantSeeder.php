<?php

namespace Database\Seeders\Tenant;

use App\Models\Company;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();

        if ($company) {
            $adminRole = Role::firstOrCreate([
                'name' => 'admin',
                'guard_name' => 'keycloak',
            ]);

            $permissions = [
                'employees.view',
                'employees.manage',
                'payroll.view',
                'payroll.manage',
            ];

            foreach ($permissions as $permission) {
                $adminRole->givePermissionTo(
                    Permission::firstOrCreate([
                        'name' => $permission,
                        'guard_name' => 'keycloak',
                    ])
                );
            }

            Employee::firstOrCreate([
                'company_id' => $company->id,
                'email' => $company->contact_email,
            ], [
                'external_id' => Str::uuid()->toString(),
                'first_name' => 'Admin',
                'last_name' => 'User',
                'phone' => null,
                'salary' => 0,
                'currency' => 'AED',
            ]);
        }
    }
}
