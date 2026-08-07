<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use ZipArchive;

class BulkImportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->seed();
    }

    private function csvUpload(string $content): UploadedFile
    {
        return UploadedFile::fake()->createWithContent('products.csv', $content);
    }

    private function zipUpload(array $files): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'ps-ziptest');
        $zip = new ZipArchive;
        $zip->open($path, ZipArchive::OVERWRITE);

        foreach ($files as $name => $contents) {
            $zip->addFromString($name, $contents);
        }

        $zip->close();

        return new UploadedFile($path, 'images.zip', 'application/zip', null, true);
    }

    private function adminHeaders(): array
    {
        $admin = User::query()->where('email', 'phonestation31@gmail.com')->firstOrFail();

        return [$admin];
    }

    public function test_template_downloads_csv_with_headers(): void
    {
        $admin = $this->adminHeaders()[0];

        $this->actingAs($admin)
            ->get(route('admin.products.template'))
            ->assertOk()
            ->assertHeader('content-type', 'text/csv; charset=UTF-8')
            ->assertDownload('products-import-template.csv');

        $response = $this->actingAs($admin)->get(route('admin.products.template'));
        $content = $response->streamedContent();

        $this->assertStringContainsString('name,brand,tagline,description,price', $content);
        $this->assertStringContainsString('images', $content);
    }

    public function test_bulk_import_creates_products_from_csv(): void
    {
        $admin = $this->adminHeaders()[0];

        $csv = "name,brand,tagline,description,price,compare_at_price,category,badge,stock,featured,specs,images\n"
            .'"Test Phone A","Nokia","Rugged line","A test description",450000,500000,flagships,"NEW",5,yes,"chip:A17 Pro|battery:5,000 mAh",'."\n"
            .'"Test Phone B","Motorola","Line","Another test description",100000,,budget,,10,no,,'."\n";

        $this->actingAs($admin)
            ->post(route('admin.products.import'), ['csv' => $this->csvUpload($csv)])
            ->assertRedirect(route('admin.products.bulk'));

        $product = Product::query()->where('slug', 'test-phone-a')->firstOrFail();

        $this->assertSame(45000, $product->price);
        $this->assertSame(50000, $product->compare_at_price);
        $this->assertSame('Nokia', $product->brand);
        $this->assertSame('NEW', $product->badge);
        $this->assertSame(5, $product->stock);
        $this->assertTrue($product->featured);
        $this->assertSame(['chip' => 'A17 Pro', 'battery' => '5,000 mAh'], $product->specs);
        $this->assertSame('flagships', $product->category->slug);

        $this->assertDatabaseHas('products', ['slug' => 'test-phone-b', 'price' => 10000, 'featured' => 0]);
    }

    public function test_bulk_import_attaches_multiple_images_from_zip(): void
    {
        Storage::disk('public');
        $admin = $this->adminHeaders()[0];

        $csv = "name,brand,tagline,description,price,compare_at_price,category,badge,stock,featured,specs,images\n"
            .'"Camera Phone","Canon","Shoot","A test description",250000,,flagships,,3,no,,"front.jpg;back.jpg"'."\n";

        $this->actingAs($admin)
            ->post(route('admin.products.import'), [
                'csv' => $this->csvUpload($csv),
                'zip' => $this->zipUpload(['front.jpg' => 'jpg-bytes', 'back.jpg' => 'jpg-bytes-2']),
            ])
            ->assertRedirect(route('admin.products.bulk'));

        $product = Product::query()->where('slug', 'camera-phone')->firstOrFail();

        $this->assertSame(2, $product->images()->count());
        $this->assertSame('products/camera-phone/front.jpg', $product->images()->first()->image);
        $this->assertSame('products/camera-phone/front.jpg', $product->image);

        $this->assertTrue(Storage::disk('public')->exists('products/camera-phone/front.jpg'));
        $this->assertTrue(Storage::disk('public')->exists('products/camera-phone/back.jpg'));
    }

    public function test_bulk_import_reports_skipped_rows(): void
    {
        $admin = $this->adminHeaders()[0];

        $csv = "name,brand,tagline,description,price,compare_at_price,category,badge,stock,featured,specs,images\n"
            .'"Valid One","Nokia","Line","Desc",450000,,flagships,,5,no,,'."\n"
            .'"Broken One","Nokia","Line","Desc",450000,,missing-category,,5,no,,'."\n"
            .'"Broken Two","Nokia","Line","Desc",not-a-price,,flagships,,5,no,,'."\n";

        $this->actingAs($admin)
            ->post(route('admin.products.import'), ['csv' => $this->csvUpload($csv)])
            ->assertRedirect(route('admin.products.bulk'))
            ->assertSessionHas('import_errors');

        $this->assertDatabaseHas('products', ['slug' => 'valid-one']);
        $this->assertDatabaseMissing('products', ['slug' => 'broken-one']);
        $this->assertDatabaseMissing('products', ['slug' => 'broken-two']);
    }

    public function test_bulk_import_guest_is_redirected(): void
    {
        $this->get(route('admin.products.bulk'))->assertRedirect(route('login'));
    }
}
