<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
        'key',
        'name',
        'description',
    ];

    public const KEYS = [
        'dashboard.view',
        'products.manage',
        'categories.manage',
        'orders.manage',
        'users.manage',
        'permissions.manage',
    ];

    public const ROLES = [
        'admin',
        'staff',
        'customer',
    ];

    /**
     * Default permissions granted to each role (admin is implicitly granted all).
     */
    public static function defaults(): array
    {
        return [
            'admin' => self::KEYS,
            'staff' => ['dashboard.view', 'products.manage', 'categories.manage', 'orders.manage'],
            'customer' => [],
        ];
    }
}
