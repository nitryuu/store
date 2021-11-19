<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Order extends Model
{
    use HasFactory, BelongsToTenant;

    protected $guarded = [];

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format("Y-m-d H:i:s");
    }

    public function branch()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function supplier()
    {
        return $this->hasOne(Supplier::class, 'id', 'supplier_id');
    }
}
