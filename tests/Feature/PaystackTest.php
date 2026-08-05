<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Services\Paystack;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PaystackTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('paystack.secret_key', 'sk_test_123');
        config()->set('paystack.public_key', 'pk_test_123');
    }

    private function addToCart(Product $product): void
    {
        $this->withSession(['cart' => [$product->id => 1]]);
    }

    private function checkoutPayload(Product $product, string $method = 'paystack'): array
    {
        return [
            'customer_name' => 'Ada Obi',
            'customer_email' => 'ada@example.com',
            'customer_phone' => '+234 801 000 1111',
            'shipping_address' => '10 Sample Street',
            'shipping_city' => 'Ibadan',
            'shipping_postal_code' => '200001',
            'shipping_country' => 'Nigeria',
            'payment_method' => $method,
        ];
    }

    public function test_checkout_initializes_paystack_and_redirects(): void
    {
        $this->seed();
        $product = Product::first();

        Http::fake([
            'api.paystack.co/transaction/initialize' => Http::response([
                'status' => true,
                'message' => 'Authorization URL created',
                'data' => [
                    'authorization_url' => 'https://checkout.paystack.com/abc123',
                    'reference' => 'PS-TESTREF',
                ],
            ]),
        ]);

        $this->addToCart($product);

        $this->post('/checkout', $this->checkoutPayload($product))
            ->assertRedirect('https://checkout.paystack.com/abc123');

        $order = Order::query()->latest('id')->firstOrFail();

        Http::assertSent(fn (Request $request) => $request->url() === 'https://api.paystack.co/transaction/initialize'
            && $request['amount'] === Paystack::amountInKobo($order)
            && $request['email'] === 'ada@example.com');

        $this->assertNotNull($order->payment_reference);
        $this->assertSame('paystack', $order->payment_method);
        $this->assertNull($order->paid_at);

        $this->assertNull(session('cart'));
    }

    public function test_callback_verifies_payment_and_marks_order_paid(): void
    {
        $this->seed();
        $product = Product::first();
        $order = $this->createUnpaidOrder($product);

        Http::fake([
            "api.paystack.co/transaction/verify/{$order->payment_reference}" => Http::response([
                'status' => true,
                'data' => [
                    'status' => 'success',
                    'amount' => Paystack::amountInKobo($order),
                ],
            ]),
        ]);

        $this->get("/payments/callback?reference={$order->payment_reference}&trxref={$order->payment_reference}")
            ->assertRedirect(route('checkout.confirmation', $order));

        $order->refresh();

        $this->assertSame('paid', $order->status);
        $this->assertNotNull($order->paid_at);
    }

    public function test_callback_does_not_pay_when_amount_mismatches(): void
    {
        $this->seed();
        $product = Product::first();
        $order = $this->createUnpaidOrder($product);

        Http::fake([
            "api.paystack.co/transaction/verify/{$order->payment_reference}" => Http::response([
                'status' => true,
                'data' => [
                    'status' => 'success',
                    'amount' => 1,
                ],
            ]),
        ]);

        $this->get("/payments/callback?reference={$order->payment_reference}")
            ->assertRedirect(route('checkout.confirmation', $order));

        $this->assertNull($order->fresh()->paid_at);
        $this->assertSame('placed', $order->fresh()->status);
    }

    public function test_cod_checkout_skips_paystack(): void
    {
        $this->seed();
        $product = Product::first();

        Http::assertNothingSent();

        $this->addToCart($product);

        $this->post('/checkout', $this->checkoutPayload($product, 'cod'))
            ->assertRedirect(route('checkout.confirmation', Order::query()->latest('id')->firstOrFail()));

        $order = Order::first();

        $this->assertSame('cod', $order->payment_method);
        $this->assertNull($order->payment_reference);
        $this->assertNull($order->paid_at);
    }

    public function test_webhook_with_valid_signature_marks_order_paid(): void
    {
        $this->seed();
        $product = Product::first();
        $order = $this->createUnpaidOrder($product);

        $payload = json_encode([
            'event' => 'charge.success',
            'data' => ['reference' => $order->payment_reference],
        ]);

        $signature = hash_hmac('sha512', $payload, config('paystack.secret_key'));

        $this->postJson('/webhooks/paystack', json_decode($payload, true), [
            'x-paystack-signature' => $signature,
        ])->assertOk();

        $order->refresh();

        $this->assertNotNull($order->paid_at);
        $this->assertSame('paid', $order->status);
    }

    public function test_webhook_rejects_bad_signature(): void
    {
        $this->seed();
        $product = Product::first();
        $order = $this->createUnpaidOrder($product);

        $payload = json_encode([
            'event' => 'charge.success',
            'data' => ['reference' => $order->payment_reference],
        ]);

        $this->postJson('/webhooks/paystack', json_decode($payload, true), [
            'x-paystack-signature' => 'forged',
        ])->assertStatus(401);

        $this->assertNull($order->fresh()->paid_at);
    }

    private function createUnpaidOrder(Product $product): Order
    {
        $reference = 'PS-'.str()->upper(str()->random(12));

        $order = Order::create([
            'order_number' => 'PS-'.str()->upper(str()->random(6)),
            'customer_name' => 'Ada Obi',
            'customer_email' => 'ada@example.com',
            'customer_phone' => '+234 801 000 1111',
            'shipping_address' => '10 Sample Street',
            'shipping_city' => 'Ibadan',
            'shipping_postal_code' => '200001',
            'shipping_country' => 'Nigeria',
            'subtotal' => $product->price,
            'shipping' => 0,
            'total' => $product->price,
            'status' => 'placed',
            'payment_method' => 'paystack',
            'payment_reference' => $reference,
        ]);

        $order->items()->create([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_slug' => $product->slug,
            'unit_price' => $product->price,
            'quantity' => 1,
            'line_total' => $product->price,
        ]);

        return $order;
    }
}
