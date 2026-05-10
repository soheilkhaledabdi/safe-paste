<?php

namespace Tests\Feature;

use App\Models\Paste;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;

class AdminPanelFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_admin_resources(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $paste = Paste::create([
            'user_id' => $admin->id,
            'slug' => 'adminpaste123',
            'title' => 'Admin managed paste',
            'content' => Crypt::encryptString('admin content'),
            'visibility' => 'private',
        ]);

        $this->actingAs($admin)
            ->get(route('filament.admin.resources.pastes.index'))
            ->assertOk()
            ->assertSee('Admin managed paste');

        $this->actingAs($admin)
            ->get(route('filament.admin.resources.users.index'))
            ->assertOk()
            ->assertSee($admin->email);

        $this->actingAs($admin)
            ->get(route('filament.admin.resources.pastes.create'))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('filament.admin.resources.pastes.view', ['record' => $paste]))
            ->assertOk()
            ->assertSee('admin content');

        $this->actingAs($admin)
            ->get(route('filament.admin.resources.users.create'))
            ->assertOk();
    }
}
