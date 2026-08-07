<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoOrderSeeder extends Seeder
{
    /**
     * Seed demo customers and orders so the admin dashboard has data to show.
     */
    public function run(): void
    {
        $adminEmail = 'phonestation31@gmail.com';

        $admin = User::query()->updateOrCreate(
            ['email' => $adminEmail],
            ['name' => 'Store Admin', 'role' => 'admin', 'password' => 'password']
        );

        $legacyAdmin = User::query()->where('email', 'admin@example.com')->whereKeyNot($admin->getKey())->first();

        if ($legacyAdmin) {
            $legacyAdmin->delete();
        }

        if (Order::query()->exists()) {
            return;
        }

        $customers = collect([
            ['name' => 'John Doe', 'email' => 'john@example.com'],
            ['name' => 'Jane Smith', 'email' => 'jane@example.com'],
            ['name' => 'Kemi Ade', 'email' => 'kemi@example.com'],
            ['name' => 'Sam Wilson', 'email' => 'sam@example.com'],
            ['name' => 'Aisha Bello', 'email' => 'aisha@example.com'],
            ['name' => 'David Okafor', 'email' => 'david@example.com'],
        ])->map(function ($customer) {
            $user = User::query()->firstOrCreate(['email' => $customer['email']], [
                'name' => $customer['name'],
                'password' => 'password',
                'role' => 'customer',
            ]);

            return $user;
        });

        $products = Product::query()->get();
        $statuses = ['placed', 'placed', 'paid', 'paid', 'processing', 'processing', 'shipped', 'shipped', 'delivered', 'delivered', 'delivered', 'delivered', 'cancelled'];

        foreach (range(1, 60) as $i) {
            $customer = $customers->random();
            $createdAt = now()->subDays(rand(1, 340))->setTime(rand(9, 18), rand(0, 59));
            $items = collect([]);
            $subtotal = 0;

            foreach (range(1, rand(1, 3)) as $j) {
                $product = $products->random();
                $quantity = rand(1, 2);
                $lineTotal = $product->price * $quantity;
                $subtotal += $lineTotal;

                $items->push([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_slug' => $product->slug,
                    'unit_price' => $product->price,
                    'quantity' => $quantity,
                    'line_total' => $lineTotal,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }

            $shipping = $subtotal >= 50000 ? 0 : 399;
            $status = $statuses[array_rand($statuses)];

            $order = Order::query()->create([
                'order_number' => 'PS-'.Str::upper(Str::random(6)),
                'customer_name' => $customer->name,
                'customer_email' => $customer->email,
                'customer_phone' => '+234 801 000 '.rand(1000, 9999),
                'shipping_address' => rand(1, 250).' Sample Street',
                'shipping_city' => ['Ibadan', 'Lagos', 'Abuja', 'Port Harcourt'][array_rand(['Ibadan', 'Lagos', 'Abuja', 'Port Harcourt'])],
                'shipping_postal_code' => null,
                'shipping_country' => 'Nigeria',
                'subtotal' => $subtotal,
                'shipping' => $shipping,
                'total' => $subtotal + $shipping,
                'status' => $status,
                'created_at' => $createdAt,
                'updated_at' => $status === 'delivered' ? $createdAt->addDays(rand(2, 6)) : $createdAt,
            ]);

            OrderItem::query()->insert($items->map(fn ($item) => [...$item, 'order_id' => $order->id])->all());
        }
    }
}
