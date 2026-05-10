<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePasteRequest;
use App\Models\Paste;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PasteController extends Controller
{
    public function create(): View
    {
        return view('home', [
            'languages' => Paste::LANGUAGES,
        ]);
    }

    public function store(StorePasteRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $user = $request->user();

        $paste = Paste::create([
            'user_id' => $user?->id,
            'slug' => $this->generateUniqueSlug(),
            'title' => $validated['title'] ?? null,
            'content' => Crypt::encryptString($validated['content']),
            'language' => $validated['language'] ?? 'text',
            'password_hash' => filled($validated['password'] ?? null) ? Hash::make($validated['password']) : null,
            'visibility' => $user ? ($validated['visibility'] ?? 'unlisted') : 'unlisted',
            'expires_at' => $this->expiresAt($validated['expires_in'] ?? 'never'),
            'burn_after_reading' => $request->boolean('burn_after_reading'),
            'max_views' => $validated['max_views'] ?? null,
            'delete_token' => $user ? null : Str::random(64),
        ]);

        return redirect()->route('pastes.created', $paste);
    }

    public function created(Paste $paste): View
    {
        return view('pastes.created', [
            'paste' => $paste,
            'pasteUrl' => route('pastes.show', $paste->slug),
        ]);
    }

    public function show(Request $request, string $slug): View|Response
    {
        $paste = Paste::where('slug', $slug)->firstOrFail();
        $goneReason = $this->goneReason($paste);

        if ($goneReason !== null) {
            return response()->view('pastes.gone', [
                'paste' => $paste,
                'reason' => $goneReason,
            ], 410);
        }

        if ($paste->visibility === 'private' && ! $request->user()?->is($paste->user)) {
            abort(403);
        }

        if (
            $paste->isPasswordProtected()
            && ! $request->session()->get($this->passwordSessionKey($paste), false)
        ) {
            return view('pastes.password', [
                'paste' => $paste,
            ]);
        }

        $content = $paste->decryptContent();

        $paste->forceFill([
            'views_count' => $paste->views_count + 1,
            'last_viewed_at' => now(),
            'read_at' => $paste->burn_after_reading && $paste->read_at === null ? now() : $paste->read_at,
        ])->save();

        return view('pastes.show', [
            'paste' => $paste->refresh(),
            'content' => $content,
        ]);
    }

    public function verifyPassword(Request $request, string $slug): RedirectResponse
    {
        $paste = Paste::where('slug', $slug)->firstOrFail();

        if (! $paste->isPasswordProtected()) {
            return redirect()->route('pastes.show', $paste->slug);
        }

        $validated = $request->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Hash::check($validated['password'], $paste->password_hash)) {
            return back()
                ->withErrors(['password' => 'The password is incorrect.'])
                ->onlyInput('password');
        }

        $request->session()->put($this->passwordSessionKey($paste), true);

        return redirect()->route('pastes.show', $paste->slug);
    }

    public function destroyGuest(string $slug, string $token): RedirectResponse
    {
        $paste = Paste::where('slug', $slug)
            ->whereNull('user_id')
            ->firstOrFail();

        abort_unless(
            $paste->delete_token !== null && hash_equals($paste->delete_token, $token),
            403
        );

        $paste->delete();

        return redirect()->route('home')->with('status', __('safe_paste.dashboard.deleted'));
    }

    private function generateUniqueSlug(): string
    {
        do {
            $slug = Str::random(12);
        } while (Paste::where('slug', $slug)->exists());

        return $slug;
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

    private function goneReason(Paste $paste): ?string
    {
        if ($paste->isExpired()) {
            return 'expired';
        }

        if ($paste->isBurned()) {
            return 'burned';
        }

        if ($paste->hasReachedViewLimit()) {
            return 'view_limit';
        }

        return null;
    }

    private function passwordSessionKey(Paste $paste): string
    {
        return "paste_password_verified_{$paste->id}";
    }
}
