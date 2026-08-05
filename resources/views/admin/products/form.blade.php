@extends('layouts.admin')

@section('title', $product->exists ? 'Edit Product' : 'New Product')

@section('content')
    @php $isEdit = $product->exists; @endphp

    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-lg font-semibold">{{ $isEdit ? 'Edit Product' : 'New Product' }}</h1>
            <p class="text-xs text-ash mt-0.5">{{ $isEdit ? $product->name : 'Add a new phone to the store' }}</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="text-sm font-medium text-ash hover:text-brand">← Back to products</a>
    </div>

    <form method="POST" action="{{ $isEdit ? route('admin.products.update', $product) : route('admin.products.store') }}"
          enctype="multipart/form-data" class="mt-5 grid grid-cols-1 xl:grid-cols-3 gap-5">
        @csrf
        @method($isEdit ? 'PUT' : 'POST')

        {{-- MAIN COLUMN --}}
        <div class="xl:col-span-2 space-y-5">
            <div class="bg-white border border-line rounded-xl p-6 space-y-4">
                <h2 class="font-semibold text-sm">Details</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-medium text-ash mb-1.5">Product name *</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="input-box rounded-lg">
                        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-ash mb-1.5">Brand *</label>
                        <input type="text" name="brand" value="{{ old('brand', $product->brand) }}" required class="input-box rounded-lg">
                        @error('brand') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-ash mb-1.5">Slug</label>
                        <input type="text" name="slug" value="{{ old('slug', $product->slug) }}" placeholder="auto-generated" class="input-box rounded-lg">
                        @error('slug') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-medium text-ash mb-1.5">Tagline *</label>
                        <input type="text" name="tagline" value="{{ old('tagline', $product->tagline) }}" required class="input-box rounded-lg">
                        @error('tagline') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-medium text-ash mb-1.5">Description *</label>
                        <textarea name="description" rows="5" required class="input-box rounded-lg">{{ old('description', $product->description) }}</textarea>
                        @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="bg-white border border-line rounded-xl p-6 space-y-4">
                <h2 class="font-semibold text-sm">Pricing & Stock</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-ash mb-1.5">Price (₦) *</label>
                        <input type="number" name="price" min="0" step="any" value="{{ old('price', $product->exists ? $product->price * 10 : '') }}" required class="input-box rounded-lg">
                        @error('price') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-ash mb-1.5">Compare-at price (₦)</label>
                        <input type="number" name="compare_at_price" min="0" step="any" value="{{ old('compare_at_price', $product->exists && $product->compare_at_price ? $product->compare_at_price * 10 : '') }}" class="input-box rounded-lg">
                        <p class="mt-1 text-[11px] text-ash">Higher "was" price used to show a discount.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-ash mb-1.5">Stock *</label>
                        <input type="number" name="stock" min="0" value="{{ old('stock', $product->stock ?? 0) }}" required class="input-box rounded-lg">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-ash mb-1.5">Badge</label>
                        <input type="text" name="badge" value="{{ old('badge', $product->badge) }}" placeholder="e.g. NEW, BEST SELLER" class="input-box rounded-lg">
                    </div>
                </div>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="featured" value="1" @checked(old('featured', $product->featured)) class="w-4 h-4 accent-brand">
                    <span class="text-sm">Feature on the homepage</span>
                </label>
            </div>

            <div class="bg-white border border-line rounded-xl p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="font-semibold text-sm">Specifications</h2>
                    <button type="button" id="add-spec" class="text-xs font-semibold text-brand hover:underline">+ Add row</button>
                </div>
                <p class="text-xs text-ash -mt-2">e.g. chip → A17 Pro, display → 6.1" OLED</p>
                <div id="spec-rows" class="space-y-3">
                    @if ($product->exists && is_array($product->specs))
                        @foreach ($product->specs as $key => $value)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 spec-row">
                                <input type="text" name="specs_key[]" value="{{ $key }}" placeholder="Key" class="input-box rounded-lg">
                                <div class="flex gap-2">
                                    <input type="text" name="specs_value[]" value="{{ $value }}" placeholder="Value" class="input-box rounded-lg flex-1">
                                    <button type="button" class="remove-spec p-2 rounded-lg border border-line text-muted hover:text-red-600">✕</button>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        {{-- SIDEBAR COLUMN --}}
        <div class="space-y-5">
            <div class="bg-white border border-line rounded-xl p-6 space-y-4">
                <h2 class="font-semibold text-sm">Category *</h2>
                <select name="category_id" required class="input-box rounded-lg">
                    <option value="">Choose a category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="bg-white border border-line rounded-xl p-6 space-y-4">
                <h2 class="font-semibold text-sm">Image</h2>
                <div id="image-preview" class="w-full h-40 rounded-lg bg-mist flex items-center justify-center overflow-hidden">
                    @if ($product->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($product->image))
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($product->image) }}" class="w-full h-full object-contain">
                    @else
                        <span class="text-xs text-ash">No image</span>
                    @endif
                </div>
                <input type="file" name="image" id="image-input" accept="image/*" class="text-xs text-ash file:mr-3 file:px-3 file:py-2 file:rounded-lg file:border-0 file:bg-mist file:text-ink file:text-xs file:font-semibold hover:file:bg-brand hover:file:text-white transition-colors">
                @error('image') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="bg-white border border-line rounded-xl p-6 flex gap-3">
                <button type="submit" class="flex-1 bg-brand text-white text-sm font-semibold px-4 py-3 rounded-lg hover:bg-brand-dark transition-colors">
                    {{ $isEdit ? 'Save Changes' : 'Create Product' }}
                </button>
                @if ($isEdit)
                    <a href="{{ route('products.show', $product) }}" target="_blank"
                       class="px-4 py-3 rounded-lg border border-line text-sm font-semibold text-ash hover:text-brand hover:border-brand transition-colors">View</a>
                @endif
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    document.getElementById('add-spec')?.addEventListener('click', function () {
        const row = document.createElement('div');
        row.className = 'grid grid-cols-1 sm:grid-cols-2 gap-3 spec-row';
        row.innerHTML = `
            <input type="text" name="specs_key[]" placeholder="Key" class="input-box rounded-lg">
            <div class="flex gap-2">
                <input type="text" name="specs_value[]" placeholder="Value" class="input-box rounded-lg flex-1">
                <button type="button" class="remove-spec p-2 rounded-lg border border-line text-muted hover:text-red-600">✕</button>
            </div>`;
        document.getElementById('spec-rows').appendChild(row);
    });

    document.getElementById('spec-rows')?.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-spec')) {
            e.target.closest('.spec-row').remove();
        }
    });

    document.getElementById('image-input')?.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('image-preview').innerHTML =
                `<img src="${e.target.result}" class="w-full h-full object-contain">`;
        };
        reader.readAsDataURL(file);
    });
</script>
@endpush
