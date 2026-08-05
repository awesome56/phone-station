@extends('layouts.admin')

@section('title', $category->exists ? 'Edit Category' : 'New Category')

@section('content')
    @php $isEdit = $category->exists; @endphp

    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-lg font-semibold">{{ $isEdit ? 'Edit Category' : 'New Category' }}</h1>
            <p class="text-xs text-ash mt-0.5">Categories group products on the shop page</p>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="text-sm font-medium text-ash hover:text-brand">← Back to categories</a>
    </div>

    <form method="POST" action="{{ $isEdit ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
          class="mt-5 max-w-2xl bg-white border border-line rounded-xl p-6 space-y-4">
        @csrf
        @method($isEdit ? 'PUT' : 'POST')

        <div>
            <label class="block text-xs font-medium text-ash mb-1.5">Name *</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}" required class="input-box rounded-lg">
            @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-medium text-ash mb-1.5">Description</label>
            <textarea name="description" rows="4" class="input-box rounded-lg">{{ old('description', $category->description) }}</textarea>
            <p class="mt-1 text-[11px] text-ash">Slug is generated automatically from the name.</p>
            @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="pt-2">
            <button type="submit" class="bg-brand text-white text-sm font-semibold px-6 py-3 rounded-lg hover:bg-brand-dark transition-colors">
                {{ $isEdit ? 'Save Changes' : 'Create Category' }}
            </button>
        </div>
    </form>
@endsection
