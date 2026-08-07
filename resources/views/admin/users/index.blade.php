@extends('layouts.admin')

@section('title', 'Users')

@section('content')
    @php
        $roleStyles = [
            'admin' => 'bg-brand/10 text-brand',
            'staff' => 'bg-indigo-50 text-indigo-600',
            'customer' => 'bg-mist text-muted',
        ];
    @endphp

    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-lg font-semibold">Users</h1>
            <p class="text-xs text-ash mt-0.5">Manage accounts and staff roles</p>
        </div>
        <a href="{{ route('admin.permissions.index') }}"
           class="inline-flex items-center gap-2 border border-brand text-brand text-sm font-semibold px-4 py-2.5 rounded-lg hover:bg-brand hover:text-white transition-colors">
            Manage permissions →
        </a>
    </div>

    <form method="GET" action="{{ route('admin.users.index') }}"
          class="mt-5 bg-white border border-line rounded-xl p-4 flex flex-wrap items-end gap-3">
        <div class="flex-1 min-w-52">
            <label class="block text-xs font-medium text-ash mb-1.5">Search</label>
            <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Name or email…" class="input-box rounded-lg">
        </div>
        <div class="w-44">
            <label class="block text-xs font-medium text-ash mb-1.5">Role</label>
            <select name="role" class="input-box rounded-lg">
                <option value="">All roles</option>
                @foreach ($roles as $role)
                    <option value="{{ $role }}" @selected(($filters['role'] ?? '') === $role)>{{ ucfirst($role) }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-ink text-white text-sm font-semibold px-5 py-2.5 rounded-lg hover:bg-brand transition-colors">Filter</button>
        @if (collect($filters)->filter()->isNotEmpty())
            <a href="{{ route('admin.users.index') }}" class="text-xs font-medium text-ash hover:text-brand self-center">Clear</a>
        @endif
    </form>

    <div class="mt-5 bg-white border border-line rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-[11px] uppercase tracking-wider text-ash border-b border-line">
                        <th class="px-5 py-3.5 font-semibold">User</th>
                        <th class="px-5 py-3.5 font-semibold">Role</th>
                        <th class="px-5 py-3.5 font-semibold">Orders</th>
                        <th class="px-5 py-3.5 font-semibold">Joined</th>
                        <th class="px-5 py-3.5 font-semibold text-right">Change Role</th>
                        <th class="px-5 py-3.5 font-semibold text-right">Password</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @forelse ($users as $user)
                        <tr class="hover:bg-mist/50 transition-colors">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-mist text-ink flex items-center justify-center font-semibold text-xs shrink-0">
                                        {{ str($user->name)->substr(0, 1)->upper() }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-medium truncate">{{ $user->name }}</p>
                                        <p class="text-xs text-ash truncate">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full capitalize {{ $roleStyles[$user->role] ?? 'bg-mist text-muted' }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-muted">{{ $user->orders_count }}</td>
                            <td class="px-5 py-3.5 text-muted">{{ $user->created_at->format('M j, Y') }}</td>
                            <td class="px-5 py-3.5">
                                <form method="POST" action="{{ route('admin.users.role', $user) }}" class="flex items-center justify-end gap-2">
                                    @csrf @method('PATCH')
                                    <select name="role" class="input-box rounded-lg !py-2 text-xs">
                                        @foreach ($roles as $role)
                                            <option value="{{ $role }}" @selected($user->role === $role)>{{ ucfirst($role) }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="bg-ink text-white text-xs font-semibold px-3.5 py-2.5 rounded-lg hover:bg-brand transition-colors">
                                        Save
                                    </button>
                                </form>
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <button type="button" data-password-toggle="{{ $user->id }}"
                                        class="text-xs font-semibold text-brand hover:underline">
                                    Change Password
                                </button>
                            </td>
                        </tr>
                        <tr id="password-row-{{ $user->id }}" class="hidden bg-mist/40">
                            <td colspan="6" class="px-5 py-4">
                                <form method="POST" action="{{ route('admin.users.password', $user) }}"
                                      class="flex flex-wrap items-end gap-3 max-w-2xl">
                                    @csrf @method('PATCH')
                                    <div class="flex-1 min-w-44">
                                        <label class="block text-xs font-medium text-ash mb-1.5">New password (min 8 chars)</label>
                                        <input type="password" name="password" required minlength="8" placeholder="New password"
                                               class="input-box rounded-lg !py-2.5 text-sm">
                                    </div>
                                    <div class="flex-1 min-w-44">
                                        <label class="block text-xs font-medium text-ash mb-1.5">Confirm password</label>
                                        <input type="password" name="password_confirmation" required minlength="8" placeholder="Repeat password"
                                               class="input-box rounded-lg !py-2.5 text-sm">
                                    </div>
                                    <button type="submit" class="bg-brand text-white text-xs font-semibold px-4 py-2.5 rounded-lg hover:bg-brand-dark transition-colors">
                                        Set Password
                                    </button>
                                </form>
                                @error("password.{$user->id}") <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-14 text-center text-ash">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t border-line">
            {{ $users->links() }}
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('[data-password-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            document.getElementById('password-row-' + button.dataset.passwordToggle).classList.toggle('hidden');
        });
    });
</script>
@endpush
