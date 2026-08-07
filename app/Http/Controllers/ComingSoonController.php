<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ComingSoonController extends Controller
{
    public function index()
    {
        return view('coming-soon');
    }

    public function subscribe(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255', 'unique:subscribers,email'],
        ]);

        Subscriber::create($validated);

        return redirect()->route('coming-soon')
            ->with('status', 'You are on the list! We will notify you as soon as we launch.');
    }
}
