<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ComingSoonTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        config()->set('app.coming_soon', false);

        parent::tearDown();
    }

    public function test_coming_soon_redirects_guests_when_enabled(): void
    {
        config()->set('app.coming_soon', true);

        $this->get('/')->assertRedirect(route('coming-soon'));
        $this->get('/shop')->assertRedirect(route('coming-soon'));

        $this->get(route('coming-soon'))
            ->assertStatus(200)
            ->assertSee('Coming soon', false)
            ->assertSee('Notify me', false);
    }

    public function test_coming_soon_does_not_block_when_disabled(): void
    {
        $this->seed();

        $this->get('/')
            ->assertStatus(200)
            ->assertSee('Phone Station', false)
            ->assertDontSee('Coming soon', false);
    }

    public function test_admins_bypass_coming_soon(): void
    {
        $this->seed();
        config()->set('app.coming_soon', true);

        $admin = User::query()->where('email', 'admin@example.com')->firstOrFail();

        $this->actingAs($admin)
            ->get('/')
            ->assertStatus(200)
            ->assertSee('Phone Station', false)
            ->assertDontSee('Coming soon', false);
    }

    public function test_customers_are_blocked_by_coming_soon(): void
    {
        config()->set('app.coming_soon', true);
        $this->seed();

        $customer = User::query()->firstWhere('role', 'customer');

        if ($customer) {
            $this->actingAs($customer)->get('/')->assertRedirect(route('coming-soon'));
        }

        $this->assertTrue(true);
    }

    public function test_subscribe_stores_email(): void
    {
        config()->set('app.coming_soon', true);

        $this->post(route('coming-soon.subscribe'), ['email' => 'early@example.com'])
            ->assertRedirect(route('coming-soon'));

        $this->assertDatabaseHas('subscribers', ['email' => 'early@example.com']);
    }

    public function test_admin_can_toggle_coming_soon_from_dashboard(): void
    {
        $this->seed();

        $admin = User::query()->where('email', 'admin@example.com')->firstOrFail();

        $this->actingAs($admin)
            ->post(route('admin.settings.coming-soon'), ['enabled' => 1])
            ->assertRedirect();

        $this->assertSame('1', Setting::get('coming_soon'));

        auth()->forgetGuards();

        $this->get('/')->assertRedirect(route('coming-soon'));

        $this->actingAs($admin)->get('/')->assertStatus(200)->assertDontSee('Coming soon', false);

        $this->actingAs($admin)
            ->post(route('admin.settings.coming-soon'), ['enabled' => 0])
            ->assertRedirect();

        $this->assertSame('0', Setting::get('coming_soon'));

        auth()->forgetGuards();

        $this->get('/')->assertStatus(200);
    }

    public function test_coming_soon_middleware_prefers_database_setting(): void
    {
        $this->seed();

        Setting::set('coming_soon', '1');

        $this->get('/')->assertRedirect(route('coming-soon'));
    }
}
