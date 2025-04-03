<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = ['uuid', 'name', 'api_key'];

    protected static ?Tenant $currentTenant = null;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public static function setCurrentTenant(?Tenant $tenant): void
    {
        static::$currentTenant = $tenant;
    }

    public static function getCurrentTenant(): ?Tenant
    {
        return static::$currentTenant;
    }
}
