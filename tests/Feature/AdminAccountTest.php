<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAccountTest extends TestCase
{
    use RefreshDatabase;

    private function admin(array $attributes = []): User
    {
        return User::factory()->create(['is_admin' => true, 'email' => 'admin@example.com'] + $attributes);
    }

    public function test_account_page_requires_an_admin(): void
    {
        $this->get(route('admin.account.edit'))->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create(['is_admin' => false]))
            ->get(route('admin.account.edit'))
            ->assertForbidden();
    }

    public function test_admin_sees_account_page_and_menu_link(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.account.edit'))
            ->assertOk()
            ->assertSee('Akun Saya')
            ->assertSee('admin@example.com')
            ->assertSee(route('admin.account.edit'), false);
    }

    public function test_weak_default_password_shows_a_warning_on_admin_pages(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Password admin masih default');
    }

    public function test_admin_changes_email_with_current_password(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->put(route('admin.account.profile.update'), [
            'name' => 'Admin Baru',
            'email' => 'baru@example.com',
            'current_password' => 'wrong-password',
        ])->assertSessionHasErrorsIn('profile', 'current_password');
        $this->assertSame('admin@example.com', $admin->fresh()->email);

        $this->put(route('admin.account.profile.update'), [
            'name' => 'Admin Baru',
            'email' => 'baru@example.com',
            'current_password' => 'password',
        ])->assertRedirect(route('admin.account.edit'))->assertSessionHasNoErrors();

        $this->assertSame('baru@example.com', $admin->fresh()->email);
        $this->assertSame('Admin Baru', $admin->fresh()->name);
    }

    public function test_email_must_be_unique(): void
    {
        User::factory()->create(['email' => 'taken@example.com']);

        $this->actingAs($this->admin())->put(route('admin.account.profile.update'), [
            'name' => 'Admin', 'email' => 'taken@example.com', 'current_password' => 'password',
        ])->assertSessionHasErrorsIn('profile', 'email');
    }

    public function test_password_change_enforces_strength_and_confirmation(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin);

        foreach ([
            ['current_password' => 'wrong', 'password' => 'KuatSekali2026', 'password_confirmation' => 'KuatSekali2026'],
            ['current_password' => 'password', 'password' => 'short1A', 'password_confirmation' => 'short1A'],
            ['current_password' => 'password', 'password' => 'alllowercase2026', 'password_confirmation' => 'alllowercase2026'],
            ['current_password' => 'password', 'password' => 'KuatSekali2026', 'password_confirmation' => 'Berbeda2026xx'],
        ] as $payload) {
            $this->put(route('admin.account.password.update'), $payload)->assertSessionHasErrorsIn('password');
        }

        $this->assertTrue(Hash::check('password', $admin->fresh()->password));
    }

    public function test_password_change_updates_hash_and_signs_out_other_sessions(): void
    {
        config(['session.driver' => 'database']);
        $admin = $this->admin();
        \DB::table('sessions')->insert([
            'id' => 'other-device-session', 'user_id' => $admin->id, 'ip_address' => '10.0.0.1',
            'user_agent' => 'Other', 'payload' => '', 'last_activity' => time(),
        ]);

        $this->actingAs($admin)->put(route('admin.account.password.update'), [
            'current_password' => 'password',
            'password' => 'KuatSekali2026',
            'password_confirmation' => 'KuatSekali2026',
        ])->assertRedirect(route('admin.account.edit'))->assertSessionHasNoErrors();

        $this->assertTrue(Hash::check('KuatSekali2026', $admin->fresh()->password));
        $this->assertFalse(\DB::table('sessions')->where('id', 'other-device-session')->exists());

        $this->get(route('admin.dashboard'))->assertOk()->assertDontSee('Password admin masih default');
    }

    public function test_account_updates_are_rate_limited(): void
    {
        $this->actingAs($this->admin());

        for ($i = 0; $i < 6; $i++) {
            $this->put(route('admin.account.password.update'), ['current_password' => 'wrong'.$i]);
        }

        $this->put(route('admin.account.password.update'), ['current_password' => 'wrong'])->assertStatus(429);
    }
}
