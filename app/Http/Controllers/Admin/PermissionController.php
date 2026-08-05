<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    public function index()
    {
        return view('admin.permissions.index', [
            'permissions' => Permission::query()->orderBy('key')->get(),
            'roles' => Permission::ROLES,
            'matrix' => $this->matrix(),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'permissions' => ['required', 'array'],
            'permissions.*' => ['array'],
            'permissions.*.*' => ['boolean'],
        ]);

        DB::transaction(function () use ($validated) {
            foreach (Permission::ROLES as $role) {
                DB::table('role_permissions')->where('role', $role)->delete();
            }

            foreach ($validated['permissions'] as $role => $keys) {
                $permissionIds = Permission::query()
                    ->whereIn('key', array_keys(array_filter($keys)))
                    ->pluck('id');

                foreach ($permissionIds as $permissionId) {
                    DB::table('role_permissions')->insert([
                        'role' => $role,
                        'permission_id' => $permissionId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            foreach (Permission::ROLES as $role) {
                Cache::forget("role_permissions:{$role}");
            }
        });

        return back()->with('status', 'Permissions updated.');
    }

    private function matrix(): array
    {
        $rows = DB::table('role_permissions')
            ->join('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
            ->get(['role_permissions.role', 'permissions.key']);

        return $rows->groupBy('role')->map(fn ($items) => $items->pluck('key')->all())->all();
    }
}
