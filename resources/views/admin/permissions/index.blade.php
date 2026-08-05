@extends('layouts.admin')

@section('title', 'Permissions')

@section('content')
    @php
        $roleNotes = [
            'admin' => 'Full access — admins bypass the permission matrix.',
            'staff' => 'Store operators — sales, products and orders.',
            'customer' => 'Regular shoppers — storefront only.',
        ];
    @endphp

    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-lg font-semibold">Permissions</h1>
            <p class="text-xs text-ash mt-0.5">Grant capabilities to each role</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-ash hover:text-brand">← Back to users</a>
    </div>

    <form method="POST" action="{{ route('admin.permissions.update') }}"
          class="mt-5 bg-white border border-line rounded-xl overflow-hidden">
        @csrf

        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[640px]">
                <thead>
                    <tr class="text-left text-[11px] uppercase tracking-wider text-ash border-b border-line">
                        <th class="px-6 py-4 font-semibold w-1/2">Permission</th>
                        @foreach ($roles as $role)
                            <th class="px-6 py-4 font-semibold text-center capitalize">{{ $role }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @foreach ($permissions as $permission)
                        <tr class="hover:bg-mist/50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="font-medium">{{ $permission->name }}</p>
                                <p class="text-xs text-ash mt-0.5">{{ $permission->key }}</p>
                            </td>
                            @foreach ($roles as $role)
                                <td class="px-6 py-4 text-center">
                                    @if ($role === 'admin')
                                        <span class="text-xs text-ash">Always</span>
                                    @else
                                        <input type="checkbox"
                                               name="permissions[{{ $role }}][{{ $permission->key }}]"
                                               value="1"
                                               @checked(in_array($permission->key, $matrix[$role] ?? [], true))
                                               class="w-4 h-4 accent-brand">
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-line flex flex-wrap items-center justify-between gap-3 bg-mist/30">
            <div class="text-xs text-ash">
                @foreach ($roleNotes as $role => $note)
                    <p><span class="font-semibold capitalize">{{ $role }}:</span> {{ $note }}</p>
                @endforeach
            </div>
            <button type="submit" class="bg-brand text-white text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-brand-dark transition-colors">
                Save Permissions
            </button>
        </div>
    </form>
@endsection
