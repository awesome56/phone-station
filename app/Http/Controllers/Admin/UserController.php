<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()
            ->withCount('orders')
            ->when($request->filled('q'), function ($q) use ($request) {
                $q->where(fn ($q) => $q
                    ->where('name', 'like', "%{$request->q}%")
                    ->orWhere('email', 'like', "%{$request->q}%"));
            })
            ->when($request->filled('role'), fn ($q) => $q->where('role', $request->role))
            ->latest();

        return view('admin.users.index', [
            'users' => $query->paginate(15)->withQueryString(),
            'roles' => Permission::ROLES,
            'filters' => $request->only(['q', 'role']),
        ]);
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => ['required', 'in:'.implode(',', Permission::ROLES)],
        ]);

        if ($user->getKey() === $request->user()->getKey() && $user->role === 'admin' && $request->role !== 'admin') {
            return back()->with('error', 'You cannot demote your own admin account.');
        }

        $user->update(['role' => $request->role]);

        return back()->with('status', "{$user->name} is now a {$request->role}.");
    }
}
