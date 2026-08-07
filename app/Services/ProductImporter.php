<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class ProductImporter
{
    public const HEADERS = [
        'name',
        'brand',
        'tagline',
        'description',
        'price',
        'compare_at_price',
        'category',
        'badge',
        'stock',
        'featured',
        'specs',
        'images',
    ];

    /**
     * @return array{imported: int, errors: array<int, string>}
     */
    public function import(string $csvPath, ?string $zipPath = null): array
    {
        $rows = $this->parse($csvPath);

        if ($rows === []) {
            return ['imported' => 0, 'errors' => ['The CSV file is empty or has no data rows.']];
        }

        $imageFiles = $zipPath ? $this->extractImages($zipPath) : [];

        $categories = Category::query()->pluck('id', 'slug');
        $imported = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            $line = $index + 2; // 1-based, header is line 1

            try {
                $product = $this->createProduct($row, $categories, $imageFiles);
                $imported++;
            } catch (\Throwable $e) {
                $errors[] = "Line {$line}: {$e->getMessage()}";
            }
        }

        return ['imported' => $imported, 'errors' => $errors];
    }

    public function template(): string
    {
        $lines = [];
        $lines[] = implode(',', self::HEADERS);

        $lines[] = implode(',', [
            '"iPhone 15 Pro Max"',
            '"Apple"',
            '"Titanium. Blistering. Unmistakable."',
            '"Example row — replace or delete before uploading. 48MP camera, A17 Pro chip."',
            '1299000',
            '1399000',
            'flagships',
            '"NEW"',
            '10',
            'yes',
            '"chip:A17 Pro|display:6.7" ProMotion OLED"',
            '"phone-front.jpg;phone-back.jpg"',
        ]);

        $lines[] = implode(',', [
            '"Galaxy A55"',
            '"Samsung"',
            '"Mid-range done right."',
            '"Second example row. Category must match an existing slug: flagships, foldables, budget, gaming."',
            '480000',
            '',
            'budget',
            '',
            '25',
            'no',
            '"chip:Exynos 1480|battery:5,000 mAh"',
            '',
        ]);

        return "\xEF\xBB\xBF".implode("\r\n", $lines)."\r\n";
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function parse(string $csvPath): array
    {
        $handle = fopen($csvPath, 'r');

        if ($handle === false) {
            return [];
        }

        $headers = null;
        $rows = [];

        while (($line = fgetcsv($handle, 0, ',', '"', '\\')) !== false) {
            if ($line === [null]) {
                continue;
            }

            if ($headers === null) {
                $headers = array_map(
                    fn ($header) => Str::lower(Str::replace(' ', '_', trim($header))),
                    $line
                );

                continue;
            }

            $rows[] = array_combine(
                $headers,
                array_pad(array_map(fn ($v) => trim((string) $v), $line), count($headers), '')
            );
        }

        fclose($handle);

        return $rows;
    }

    /**
     * Extract a ZIP of product images to a temp directory, keyed by basename.
     * Entries are written with their basename only (path traversal is ignored).
     *
     * @return array<string, string> basename => absolute path
     */
    private function extractImages(string $zipPath): array
    {
        $zip = new ZipArchive;

        if ($zip->open($zipPath) !== true) {
            throw new \RuntimeException('Could not open the image ZIP file.');
        }

        $dir = sys_get_temp_dir().'/ps-import-'.Str::random(10);
        @mkdir($dir, 0775, true);

        $files = [];

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $name = $zip->getNameIndex($i);
            $base = basename($name);

            if ($base === '' || ! preg_match('/\.(jpg|jpeg|png|webp|gif|avif)$/i', $base)) {
                continue;
            }

            $contents = $zip->getFromIndex($i);

            if ($contents === false) {
                continue;
            }

            $path = $dir.'/'.$base;
            file_put_contents($path, $contents);

            if (! isset($files[$base])) {
                $files[$base] = $path;
            }
        }

        $zip->close();

        return $files;
    }

    /**
     * @param  array<string, string>  $row
     * @param  Collection<int, int>  $categories
     * @param  array<string, string>  $imageFiles
     */
    private function createProduct(array $row, $categories, array $imageFiles): Product
    {
        $name = $this->required($row, 'name');
        $categorySlug = $this->required($row, 'category');
        $categoryId = $categories[$categorySlug] ?? null;

        if ($categoryId === null) {
            throw new \RuntimeException("Unknown category \"{$categorySlug}\" (use: flagships, foldables, budget, gaming).");
        }

        $price = $this->price($row, 'price');

        $product = Product::create([
            'category_id' => $categoryId,
            'name' => $name,
            'slug' => $this->uniqueSlug($name),
            'brand' => $this->required($row, 'brand'),
            'tagline' => $this->required($row, 'tagline'),
            'description' => $this->required($row, 'description'),
            'price' => $price,
            'compare_at_price' => $this->optionalPrice($row, 'compare_at_price'),
            'badge' => trim($row['badge'] ?? '') ?: null,
            'stock' => (int) ($row['stock'] ?? 0),
            'featured' => $this->bool($row, 'featured'),
            'in_stock' => (int) ($row['stock'] ?? 0) > 0,
            'specs' => $this->specs($row),
        ]);

        $images = $this->imageNames($row);

        foreach ($images as $position => $fileName) {
            if (! isset($imageFiles[$fileName])) {
                continue;
            }

            $stored = Storage::disk('public')->putFileAs(
                "products/{$product->slug}",
                $imageFiles[$fileName],
                $fileName
            );

            ProductImage::create([
                'product_id' => $product->getKey(),
                'image' => $stored,
                'position' => $position,
            ]);
        }

        if ($product->images()->exists() && $product->image === null) {
            $product->update(['image' => $product->images()->first()->image]);
        }

        return $product;
    }

    /**
     * @param  array<string, string>  $row
     */
    private function required(array $row, string $field): string
    {
        $value = trim($row[$field] ?? '');

        if ($value === '') {
            throw new \RuntimeException("The \"{$field}\" column is required.");
        }

        return $value;
    }

    /**
     * Prices in the CSV are full naira amounts; stored amounts are kobo-scale (naira = amount * 10).
     *
     * @param  array<string, string>  $row
     */
    private function price(array $row, string $field): int
    {
        $value = str_replace([',', '₦', ' '], '', $this->required($row, $field));

        if (! is_numeric($value) || (float) $value < 1) {
            throw new \RuntimeException("The \"{$field}\" column must be a positive number.");
        }

        return (int) round((float) $value / 10);
    }

    /**
     * @param  array<string, string>  $row
     */
    private function optionalPrice(array $row, string $field): ?int
    {
        $value = str_replace([',', '₦', ' '], '', trim($row[$field] ?? ''));

        if ($value === '') {
            return null;
        }

        if (! is_numeric($value)) {
            throw new \RuntimeException("The \"{$field}\" column must be a number.");
        }

        return (int) round((float) $value / 10);
    }

    /**
     * @param  array<string, string>  $row
     */
    private function bool(array $row, string $field): bool
    {
        return in_array(Str::lower(trim($row[$field] ?? '')), ['1', 'yes', 'true', 'on'], true);
    }

    /**
     * @param  array<string, string>  $row
     */
    private function specs(array $row): ?array
    {
        $raw = trim($row['specs'] ?? '');

        if ($raw === '') {
            return null;
        }

        $specs = [];

        foreach (explode('|', $raw) as $pair) {
            [$key, $value] = array_pad(explode(':', $pair, 2), 2, '');

            if (trim($key) !== '' && trim($value) !== '') {
                $specs[trim($key)] = trim($value);
            }
        }

        return $specs === [] ? null : $specs;
    }

    /**
     * @param  array<string, string>  $row
     * @return array<int, string>
     */
    private function imageNames(array $row): array
    {
        $raw = trim($row['images'] ?? '');

        if ($raw === '') {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(';', $raw)), fn ($name) => $name !== ''));
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 2;

        while (Product::query()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
