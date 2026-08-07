<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function toggleComingSoon(Request $request): RedirectResponse
    {
        $request->validate([
            'enabled' => ['required', 'boolean'],
        ]);

        Setting::set('coming_soon', $request->boolean('enabled') ? '1' : '0');

        $state = $request->boolean('enabled') ? 'enabled' : 'disabled';

        return back()->with('status', "Coming soon mode {$state}. Guests now ".($request->boolean('enabled') ? 'see the coming-soon page.' : 'see the full store.'));
    }
}
