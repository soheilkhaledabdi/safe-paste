<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasteRequest;
use App\Models\Paste;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class DashboardPasteController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $pastesQuery = $user->pastes();

        return view('dashboard.pastes.index', [
            'pastes' => (clone $pastesQuery)->latest()->paginate(15),
            'totalPastes' => (clone $pastesQuery)->count(),
            'activePastes' => (clone $pastesQuery)
                ->where(function ($query): void {
                    $query->whereNull('expires_at')->orWhere('expires_at', '>', now());
                })
                ->where(function ($query): void {
                    $query->where('burn_after_reading', false)->orWhereNull('read_at');
                })
                ->where(function ($query): void {
                    $query->whereNull('max_views')->orWhereColumn('views_count', '<', 'max_views');
                })
                ->count(),
            'totalViews' => (clone $pastesQuery)->sum('views_count'),
        ]);
    }

    public function show(Request $request, Paste $paste): View
    {
        $this->authorizeOwner($request, $paste);

        return view('dashboard.pastes.show', [
            'paste' => $paste,
            'content' => $paste->decryptContent(),
        ]);
    }

    public function edit(Request $request, Paste $paste): View
    {
        $this->authorizeOwner($request, $paste);

        return view('dashboard.pastes.edit', [
            'paste' => $paste,
            'content' => $paste->decryptContent(),
            'languages' => Paste::LANGUAGES,
        ]);
    }

    public function update(UpdatePasteRequest $request, Paste $paste): RedirectResponse
    {
        $validated = $request->validated();

        $data = [
            'title' => $validated['title'] ?? null,
            'content' => Crypt::encryptString($validated['content']),
            'language' => $validated['language'] ?? 'text',
            'visibility' => $validated['visibility'] ?? 'unlisted',
            'burn_after_reading' => $request->boolean('burn_after_reading'),
            'max_views' => $validated['max_views'] ?? null,
        ];

        if (($validated['expires_in'] ?? null) !== null) {
            $data['expires_at'] = $this->expiresAt($validated['expires_in']);
        }

        if ($request->boolean('remove_password')) {
            $data['password_hash'] = null;
        } elseif (filled($validated['password'] ?? null)) {
            $data['password_hash'] = Hash::make($validated['password']);
        }

        $paste->update($data);

        return redirect()
            ->route('dashboard.pastes.show', $paste)
            ->with('status', __('safe_paste.dashboard.updated'));
    }

    public function destroy(Request $request, Paste $paste): RedirectResponse
    {
        $this->authorizeOwner($request, $paste);

        $paste->delete();

        return redirect()
            ->route('dashboard.pastes.index')
            ->with('status', __('safe_paste.dashboard.deleted'));
    }

    private function authorizeOwner(Request $request, Paste $paste): void
    {
        abort_unless($request->user()?->is($paste->user), 403);
    }

    private function expiresAt(?string $expiresIn): ?Carbon
    {
        return match ($expiresIn) {
            '10_minutes' => now()->addMinutes(10),
            '1_hour' => now()->addHour(),
            '1_day' => now()->addDay(),
            '7_days' => now()->addDays(7),
            '30_days' => now()->addDays(30),
            default => null,
        };
    }
}
