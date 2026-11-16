<?php

namespace Tests\Feature;

use App\Models\Position;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordManagementTest extends TestCase
{
    /**
     * Test user can view the change password page.
     */
    public function test_user_can_view_change_password_page(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $response = $this->actingAs($user)->get(route('password.change'));

        $response->assertStatus(200);
        $response->assertSee('Ubah Password');
        $response->assertSee('Password Saat Ini');
    }

    /**
     * Test user can change own password successfully.
     */
    public function test_user_can_change_own_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword'),
        ]);

        $response = $this->actingAs($user)->put(route('password.update'), [
            'current_password'      => 'oldpassword',
            'password'              => 'newsecret123',
            'password_confirmation' => 'newsecret123',
        ]);

        $response->assertRedirect(route('password.change'));
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertTrue(Hash::check('newsecret123', $user->password));
    }

    /**
     * Test user cannot change password with incorrect current password.
     */
    public function test_user_cannot_change_password_with_wrong_current_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword'),
        ]);

        $response = $this->actingAs($user)->put(route('password.update'), [
            'current_password'      => 'wrongpassword',
            'password'              => 'newsecret123',
            'password_confirmation' => 'newsecret123',
        ]);

        $response->assertSessionHasErrors('current_password');

        $user->refresh();
        $this->assertTrue(Hash::check('oldpassword', $user->password));
    }

    /**
     * Test administrator can reset a user password to default 123456.
     */
    public function test_admin_can_reset_user_password_to_default_123456(): void
    {
        // Admin user with position = 1
        $admin = User::factory()->create([
            'position' => 1,
            'password' => Hash::make('adminpassword'),
        ]);

        // Regular user
        $targetUser = User::factory()->create([
            'password' => Hash::make('targetcustompass'),
        ]);

        $response = $this->actingAs($admin)->post(route('users.reset-password', $targetUser->id));

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success');

        $targetUser->refresh();
        $this->assertTrue(Hash::check('123456', $targetUser->password));
    }

    /**
     * Test non-admin cannot reset user password.
     */
    public function test_non_admin_cannot_reset_user_password(): void
    {
        // Create a non-admin position if none exists
        $pos = Position::firstOrCreate(['name' => 'Guru Kelas']);

        $regularUser = User::factory()->create([
            'position' => $pos->id,
            'password' => Hash::make('mypassword'),
        ]);

        $targetUser = User::factory()->create([
            'password' => Hash::make('originalpassword'),
        ]);

        $response = $this->actingAs($regularUser)->post(route('users.reset-password', $targetUser->id));

        $response->assertStatus(403);

        $targetUser->refresh();
        $this->assertTrue(Hash::check('originalpassword', $targetUser->password));
    }
}
