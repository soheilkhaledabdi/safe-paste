<?php

namespace Tests\Feature;

use App\Models\Paste;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasteFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_create_paste(): void
    {
        $response = $this->post('/pastes', [
            'title' => 'Guest paste',
            'content' => 'secret guest content',
            'language' => 'text',
            'expires_in' => 'never',
        ]);

        $paste = Paste::first();

        $response->assertRedirect(route('pastes.created', $paste));
        $this->assertNull($paste->user_id);
        $this->assertSame('unlisted', $paste->visibility);
        $this->assertNotNull($paste->delete_token);
        $this->assertSame('secret guest content', $paste->decryptContent());
    }

    public function test_password_protected_paste_requires_password(): void
    {
        $paste = Paste::create([
            'slug' => 'password1234',
            'content' => Crypt::encryptString('protected content'),
            'password_hash' => Hash::make('secret'),
            'visibility' => 'unlisted',
        ]);

        $this->get(route('pastes.show', $paste->slug))
            ->assertOk()
            ->assertSee('Password required');

        $this->post(route('pastes.password.verify', $paste->slug), [
            'password' => 'secret',
        ])->assertRedirect(route('pastes.show', $paste->slug));

        $this->get(route('pastes.show', $paste->slug))
            ->assertOk()
            ->assertSee('protected content');
    }

    public function test_expired_paste_returns_gone(): void
    {
        $paste = Paste::create([
            'slug' => 'expired12345',
            'content' => Crypt::encryptString('old content'),
            'visibility' => 'unlisted',
            'expires_at' => now()->subMinute(),
        ]);

        $this->get(route('pastes.show', $paste->slug))
            ->assertGone()
            ->assertSee('expired');
    }

    public function test_burn_after_reading_paste_becomes_unavailable_after_first_view(): void
    {
        $paste = Paste::create([
            'slug' => 'burn12345678',
            'content' => Crypt::encryptString('single read'),
            'visibility' => 'unlisted',
            'burn_after_reading' => true,
        ]);

        $this->get(route('pastes.show', $paste->slug))
            ->assertOk()
            ->assertSee('single read');

        $this->assertNotNull($paste->refresh()->read_at);

        $this->get(route('pastes.show', $paste->slug))
            ->assertGone()
            ->assertSee('first successful read');
    }

    public function test_private_paste_cannot_be_viewed_by_other_users(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $paste = Paste::create([
            'user_id' => $owner->id,
            'slug' => 'private12345',
            'content' => Crypt::encryptString('private content'),
            'visibility' => 'private',
        ]);

        $this->actingAs($otherUser)
            ->get(route('pastes.show', $paste->slug))
            ->assertForbidden();
    }

    public function test_authenticated_user_can_manage_own_pastes(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/pastes', [
                'title' => 'Managed paste',
                'content' => 'managed content',
                'language' => 'php',
                'expires_in' => 'never',
                'visibility' => 'private',
            ])
            ->assertRedirect();

        $paste = Paste::first();

        $this->assertTrue($user->is($paste->user));
        $this->assertSame('private', $paste->visibility);

        $this->actingAs($user)
            ->get(route('dashboard.pastes.index'))
            ->assertOk()
            ->assertSee('Managed paste');

        $this->actingAs($user)
            ->put(route('dashboard.pastes.update', $paste), [
                'title' => 'Updated paste',
                'content' => 'updated content',
                'language' => 'javascript',
                'visibility' => 'unlisted',
                'expires_in' => 'never',
            ])
            ->assertRedirect(route('dashboard.pastes.show', $paste));

        $this->assertSame('updated content', $paste->refresh()->decryptContent());

        $this->actingAs($user)
            ->delete(route('dashboard.pastes.destroy', $paste))
            ->assertRedirect(route('dashboard.pastes.index'));

        $this->assertDatabaseMissing('pastes', ['id' => $paste->id]);
    }
}
