<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()
            ->with('category')
            ->withCount('items')
            ->when($request->filled('q'), function ($q) use ($request) {
                $q->where(fn ($q) => $q
                    ->where('name', 'like', "%{$request->q}%")
                    ->orWhere('brand', 'like', "%{$request->q}%")
                    ->orWhere('slug', 'like', "%{$request->q}%"));
            })
            ->when($request->filled('category'), fn ($q) => $q->where('category_id', $request->category))
            ->when($request->filled('stock'), function ($q) use ($request) {
                $request->stock === 'low' && $q->where('stock', '<', 10);
                $request->stock === 'out' && $q->where('stock', 0);
                $request->stock === 'in' && $q->where('stock', '>', 0);
            })
            ->latest();

        return view('admin.products.index', [
            'products' => $query->paginate(12)->withQueryString(),
            'categories' => Category::orderBy('name')->get(),
            'filters' => $request->only(['q', 'category', 'stock']),
        ]);
    }

    public function create()
    {
        return view('admin.products.form', [
            'product' => new Product,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $data['image'] = $request->hasFile('image')
            ? $request->file('image')->store('products', 'public')
            : ($data['image'] ?? null);

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('status', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.form', [
            'product' => $product,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products.edit', $product)
            ->with('status', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return back()->with('status', 'Product deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9-]+$/'],
            'brand' => ['required', 'string', 'max:255'],
            'tagline' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:1'],
            'compare_at_price' => ['nullable', 'numeric', 'min:1'],
            'badge' => ['nullable', 'string', 'max:64'],
            'stock' => ['required', 'integer', 'min:0'],
            'featured' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'max:2048'],
            'specs_key' => ['nullable', 'array'],
            'specs_key.*' => ['nullable', 'string', 'max:64'],
            'specs_value.*' => ['nullable', 'string', 'max:255'],
        ]);

        $specs = [];
        foreach ($data['specs_key'] ?? [] as $i => $key) {
            if ($key !== null && trim($key) !== '') {
                $specs[$key] = $data['specs_value'][$i] ?? null;
            }
        }

        return [
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'slug' => ! empty($data['slug']) ? $data['slug'] : Str::slug($data['name']),
            'brand' => $data['brand'],
            'tagline' => $data['tagline'],
            'description' => $data['description'],
            'price' => (int) round($data['price'] / 10),
            'compare_at_price' => $data['compare_at_price'] ? (int) round($data['compare_at_price'] / 10) : null,
            'badge' => $data['badge'] ?: null,
            'stock' => $data['stock'],
            'featured' => $request->boolean('featured'),
            'in_stock' => (int) $data['stock'] > 0,
            'specs' => $specs ?: null,
        ];
    }
}
