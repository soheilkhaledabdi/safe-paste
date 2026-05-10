<?php

namespace Tests\Feature;

use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_reach_dashboard(): void
    {
        $this->post(route('register.store'), [
            'name' => 'Safe Paste User',
            'email' => 'user@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'user@example.com']);
    }

    public function test_user_can_login_and_logout(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticatedAs($user);

        $this->post(route('logout'))->assertRedirect(route('home'));

        $this->assertGuest();
    }

    public function test_guest_can_still_use_paste_form_without_login(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Create a secure paste')
            ->assertSee('Log in');

        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }

    public function test_user_can_switch_to_persian_locale(): void
    {
        $this->post(route('locale.update', 'fa'))->assertRedirect();

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('ساخت پیست امن')
            ->assertSee('dir="rtl"', false);
    }

    public function test_registered_users_do_not_get_admin_access_by_default(): void
    {
        $this->post(route('register.store'), [
            'name' => 'Regular User',
            'email' => 'regular@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertFalse(User::where('email', 'regular@example.com')->firstOrFail()->is_admin);
    }

    public function test_only_admin_users_can_access_admin_panel(): void
    {
        $regularUser = User::factory()->create();
        $adminUser = User::factory()->create(['is_admin' => true]);

        $this->assertFalse($regularUser->canAccessPanel(Filament::getPanel('admin')));
        $this->assertTrue($adminUser->canAccessPanel(Filament::getPanel('admin')));
    }
}
