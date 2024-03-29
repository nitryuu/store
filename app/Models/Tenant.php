<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
// use Stancl\Tenancy\Contracts\TenantWithDatabase;
// use Stancl\Tenancy\Database\Concerns\HasDatabase;
// use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant
{
    // use HasDatabase, HasDomains;

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name'
        ];
    }

    public function admin()
    {
        return $this->hasOne(User::class);
    }

    public function income()
    {
        return $this->hasMany(Income::class, 'tenant_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'tenant_id');
    }
}
