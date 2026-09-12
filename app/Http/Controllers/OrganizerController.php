<?php
namespace App\Http\Controllers;

use App\Models\Organizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OrganizerController extends Controller
{
    /**
     * Show the form for creating an organizer profile.
     */
    public function create(): View
    {
        return view('organizer.create');
    }

    /**
     * Store a newly created organizer profile in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:organizers,name'],
            'logo_url' => ['nullable', 'url', 'max:2048'],
            'mpesa_number' => ['nullable', 'string', 'max:20'],
            'bank_account' => ['nullable', 'string', 'max:100'],
        ]);

        Organizer::create([
            'user_id' => $request->user()->id,
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'logo_url' => $validated['logo_url'] ?? null,
            'payout_details' => [
                'mpesa_number' => $validated['mpesa_number'] ?? null,
                'bank_account' => $validated['bank_account'] ?? null,
            ],
        ]);

        return redirect()->route('organizer.dashboard')
            ->with('status', 'Organizer profile setup complete! You can now create and host events.');
    }
}