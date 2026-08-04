<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    private function seedStore(): void
    {
        $this->seed();
    }

    public function test_home_page_returns_a_successful_response(): void
    {
        $this->seedStore();

        $this->get('/')
            ->assertStatus(200)
            ->assertSee('New Products', false)
            ->assertSee('Phone Station', false);
    }

    public function test_catalogue_lists_products(): void
    {
        $this->seedStore();

        $this->get('/shop')
            ->assertStatus(200)
            ->assertSee('iPhone 15 Pro', false)
            ->assertSee('Galaxy Z Fold 6', false);
    }

    public function test_catalogue_filters_by_category(): void
    {
        $this->seedStore();

        $this->get('/shop?category=foldables')
            ->assertStatus(200)
            ->assertSee('Galaxy Z Fold 6', false)
            ->assertDontSee('iPhone 15 Pro', false);
    }

    public function test_product_page_shows_specs_and_price(): void
    {
        $this->seedStore();

        $this->get('/shop/iphone-15-pro')
            ->assertStatus(200)
            ->assertSee('₦1,099,000')
            ->assertSee('A17 Pro', false);
    }

    public function test_cart_add_update_and_remove_flow(): void
    {
        $this->seedStore();

        $product = Product::query()->where('slug', 'iphone-15-pro')->firstOrFail();

        $this->post(route('cart.add', $product), ['quantity' => 2])
            ->assertRedirect();

        $this->assertSame(2, array_sum(session('cart', [])));

        $this->get(route('cart.index'))
            ->assertStatus(200)
            ->assertSee('iPhone 15 Pro', false)
            ->assertSee('₦2,198,000');

        $this->patch(route('cart.update'), ['quantity' => [$product->getKey() => 1]])
            ->assertRedirect(route('cart.index'));

        $this->assertSame(1, array_sum(session('cart', [])));

        $this->delete(route('cart.remove', $product))
            ->assertRedirect(route('cart.index'));

        $this->assertSame(0, array_sum(session('cart', [])));
    }

    public function test_checkout_places_order_and_clears_cart(): void
    {
        $this->seedStore();

        $product = Product::query()->where('slug', 'iphone-15-pro')->firstOrFail();
        $stockBefore = $product->fresh()->stock;

        $this->post(route('cart.add', $product), ['quantity' => 2]);

        $this->post(route('checkout.store'), [
            'customer_name' => 'Test Operator',
            'customer_email' => 'operator@example.com',
            'customer_phone' => '07700 900123',
            'shipping_address' => '1 Raw Street',
            'shipping_city' => 'London',
            'shipping_postal_code' => 'E1 1AA',
            'shipping_country' => 'UK',
        ])->assertRedirect();

        $order = Order::query()->latest()->firstOrFail();

        $this->assertSame('Test Operator', $order->customer_name);
        $this->assertSame(2, $order->items()->firstOrFail()->quantity);
        $this->assertSame(219800, $order->subtotal);
        $this->assertSame(0, $order->shipping);
        $this->assertSame(219800, $order->total);
        $this->assertSame(0, array_sum(session('cart', [])));
        $this->assertSame($stockBefore - 2, $product->fresh()->stock);

        $this->get(route('checkout.confirmation', $order))
            ->assertStatus(200)
            ->assertSee($order->order_number)
            ->assertSee('₦2,198,000.00');
    }

    public function test_checkout_requires_an_order_number(): void
    {
        $this->seedStore();

        $this->get(route('checkout.create'))->assertNotFound();
    }
}
