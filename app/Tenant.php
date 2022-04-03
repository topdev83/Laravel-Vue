<?php

namespace App;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'company_name',
            'email'
        ];
    }

    public static function createTenant($data)
    {
        $dbName = str_replace(' ', '_', $data['domain']);
        $dbName = strtolower($dbName);
        $tenant = Tenant::create([
            'company_name' => $data['company_name'],
            'email' => $data['email'],
            'tenancy_db_name' => config('tenancy.database.prefix'). "_". $dbName,
        ]);

        $tenant->domains()
            ->create([
                'domain' => $data['domain'],
            ]);

        tenancy()->initialize($tenant);
        return $tenant;
    }

}
