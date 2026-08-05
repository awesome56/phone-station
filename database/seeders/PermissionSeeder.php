<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Seed the application's permissions and role defaults.
     */
    public function run(): void
    {
        foreach (Permission::KEYS as $key) {
            Permission::query()->firstOrCreate(
                ['key' => $key],
                ['name' => str($key)->replace('.', ' ')->title()->toString()]
            );
        }

        $permissions = Permission::query()->pluck('id', 'key');

        foreach (Permission::defaults() as $role => $keys) {
            if ($role === 'admin') {
                continue;
            }

            foreach ($keys as $key) {
                DB::table('role_permissions')->updateOrInsert(
                    ['role' => $role, 'permission_id' => $permissions[$key]],
                    ['created_at' => now(), 'updated_at' => now()]
                );
            }
        }
    }
}
