<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Seed the initial app settings, seeded from env so current
     * behaviour is preserved when the setting table is introduced.
     */
    public function run(): void
    {
        Setting::set('coming_soon', config('app.coming_soon') ? '1' : '0');
    }
}
