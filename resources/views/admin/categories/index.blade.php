@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-lg font-semibold">Categories</h1>
            <p class="text-xs text-ash mt-0.5">Organise your store catalogue</p>
        </div>
        <a href="{{ route('admin.categories.create') }}"
           class="inline-flex items-center gap-2 bg-brand text-white text-sm font-semibold px-4 py-2.5 rounded-lg hover:bg-brand-dark transition-colors">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
            Add Category
        </a>
    </div>

    <form method="GET" action="{{ route('admin.categories.index') }}"
          class="mt-5 bg-white border border-line rounded-xl p-4 flex flex-wrap items-end gap-3">
        <div class="flex-1 min-w-52">
            <label class="block text-xs font-medium text-ash mb-1.5">Search</label>
            <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Category name…" class="input-box rounded-lg">
        </div>
        <button type="submit" class="bg-ink text-white text-sm font-semibold px-5 py-2.5 rounded-lg hover:bg-brand transition-colors">Filter</button>
        @if (!empty($filters['q']))
            <a href="{{ route('admin.categories.index') }}" class="text-xs font-medium text-ash hover:text-brand self-center">Clear</a>
        @endif
    </form>

    <div class="mt-5 bg-white border border-line rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-[11px] uppercase tracking-wider text-ash border-b border-line">
                        <th class="px-5 py-3.5 font-semibold">Name</th>
                        <th class="px-5 py-3.5 font-semibold">Slug</th>
                        <th class="px-5 py-3.5 font-semibold">Products</th>
                        <th class="px-5 py-3.5 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @forelse ($categories as $category)
                        <tr class="hover:bg-mist/50 transition-colors">
                            <td class="px-5 py-3.5">
                                <p class="font-medium">{{ $category->name }}</p>
                                <p class="text-xs text-ash mt-0.5 max-w-md truncate">{{ $category->description }}</p>
                            </td>
                            <td class="px-5 py-3.5 text-muted">/shop?category={{ $category->slug }}</td>
                            <td class="px-5 py-3.5">
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-mist text-muted">{{ $category->products_count }}</span>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}"
                                       class="p-2 rounded-lg border border-line text-muted hover:text-brand hover:border-brand transition-colors" aria-label="Edit">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                          onsubmit="return confirm('Delete {{ $category->name }}? Its products will be deleted too.')">
                                        @csrf @method('DELETE')
                                        <button class="p-2 rounded-lg border border-line text-muted hover:text-red-600 hover:border-red-300 transition-colors" aria-label="Delete">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-14 text-center text-ash">No categories yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t border-line">
            {{ $categories->links() }}
        </div>
    </div>
@endsection
