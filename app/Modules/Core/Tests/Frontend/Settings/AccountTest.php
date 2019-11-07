<?php

namespace App\Modules\Core\Tests\Frontend\Settings;

use App\Modules\Core\Models\Account;
use App\Modules\Core\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_may_not_view_the_account_settings_form()
    {
        $response = $this->get(route('frontend.settings.account'));

        $response->assertRedirect($this->loginRoute());
    }

    /** @test */
    public function disabled_users_may_not_view_the_account_settings_form()
    {
        $user = $this->make(Account::class, [
            'is_enabled' => false,
        ]);

        $this->actingAs($user);
        $response = $this->get(route('frontend.settings.account'));

        $response->assertRedirect($this->loginRoute());
    }

    /** @test */
    public function enabled_backend_users_may_not_view_the_account_settings_form()
    {
        $user = $this->make(Account::class, [
            'is_enabled' => true,
            'is_backend' => true,
        ]);

        $this->actingAs($user);
        $response = $this->get(route('frontend.settings.account'));

        $response->assertRedirect(config('core.backend_routes_prefix'));
    }

    /** @test */
    public function enabled_frontend_users_can_view_the_account_settings_form()
    {
        $user = $this->make(Account::class, [
            'is_enabled' => true,
            'is_backend' => false,
        ]);

        $this->actingAs($user);
        $response = $this->get(route('frontend.settings.account'));

        $response->assertSuccessful();
        $response->assertViewIs('core::frontend.settings.account');
    }

    /** @test */
    public function guests_may_not_update_the_account_settings()
    {
        $response = $this->patch(route('frontend.settings.account'));

        $response->assertRedirect($this->loginRoute());
    }

    /** @test */
    public function disabled_users_may_not_update_the_account_settings()
    {
        $user = $this->make(Account::class, [
            'is_enabled' => false,
        ]);

        $this->actingAs($user);
        $response = $this->patch(route('frontend.settings.account'));

        $response->assertRedirect($this->loginRoute());
    }

    /** @test */
    public function enabled_backend_users_may_not_update_the_account_settings()
    {
        $user = $this->make(Account::class, [
            'is_enabled' => true,
            'is_backend' => true,
        ]);

        $this->actingAs($user);
        $response = $this->patch(route('frontend.settings.account'));

        $response->assertRedirect(config('core.backend_routes_prefix'));
    }

    /** @test */
    public function enabled_unverified_frontend_users_can_update_the_account_settings()
    {
        $user = $this->make(Account::class, [
            'is_enabled' => true,
            'is_backend' => false,
            'password' => Hash::make(Str::random(8)),
            'email_verified_at' => null,
        ]);

        $this->actingAs($user);
        $this->from(route('frontend.settings.account'));
        $response = $this->patch(route('frontend.settings.account'), [
            'email' => $newEmail = 'new-email@example.com',
            'password' => $newPassword = 'new-password',
            'password_confirmation' =>  $newPassword,
        ]);

        $response->assertRedirect(route('frontend.settings.account'));
        $this->assertEquals($newEmail, $user->email);
        $this->assertTrue(Hash::check($newPassword, $user->password));
    }

    /** @test */
    public function enabled_verified_frontend_users_can_update_the_account_settings()
    {
        $user = $this->make(Account::class, [
            'is_enabled' => true,
            'is_backend' => false,
            'password' => Hash::make(Str::random(8)),
        ]);

        $this->actingAs($user);
        $this->from(route('frontend.settings.account'));
        $response = $this->patch(route('frontend.settings.account'), [
            'email' => $newEmail = 'new-email@example.com',
            'password' => $newPassword = 'new-password',
            'password_confirmation' =>  $newPassword,
        ]);

        $response->assertRedirect(route('frontend.settings.account'));
        $this->assertEquals($newEmail, $user->email);
        $this->assertTrue(Hash::check($newPassword, $user->password));
    }

    /** @test */
    public function users_may_not_update_the_account_settings_with_passwords_not_matching()
    {
        $user = $this->make(Account::class, [
           'is_enabled' => true,
           'is_backend' => false,
           'password' => Hash::make(Str::random(8)),
       ]);

        $this->actingAs($user);
        $this->from(route('frontend.settings.account'));
        $response = $this->patch(route('frontend.settings.account'), [
           'email' => $newEmail = 'new-email@example.com',
           'password' => $newPassword = 'new-password',
           'password_confirmation' =>  'another-password',
       ]);

        $response->assertRedirect(route('frontend.settings.account'));
        $response->assertSessionHasErrors('password');
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertNotEquals($newEmail, $user->email);
        $this->assertFalse(Hash::check($newPassword, $user->password));
    }

    /** @test */
    public function users_can_update_the_account_settings_without_password()
    {
        $user = $this->make(Account::class, [
            'is_enabled' => true,
            'is_backend' => false,
        ]);

        $this->actingAs($user);
        $this->from(route('frontend.settings.account'));
        $response = $this->patch(route('frontend.settings.account'), [
            'email' => $newEmail = 'new-email@example.com',
        ]);

        $response->assertRedirect(route('frontend.settings.account'));
        $this->assertEquals($newEmail, $user->email);
    }

    /** @test */
    public function users_may_not_update_the_account_settings_without_email()
    {
        $user = $this->make(Account::class, [
            'is_enabled' => true,
            'is_backend' => false,
        ]);

        $this->actingAs($user);
        $this->from(route('frontend.settings.account'));
        $response = $this->patch(route('frontend.settings.account'));

        $response->assertRedirect(route('frontend.settings.account'));
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function users_may_not_update_the_account_settings_with_invalid_email()
    {
        $user = $this->make(Account::class, [
            'is_enabled' => true,
            'is_backend' => false,
        ]);

        $this->actingAs($user);
        $this->from(route('frontend.settings.account'));
        $response = $this->patch(route('frontend.settings.account'), [
            'email' => $newEmail = 'invalid-email',
        ]);

        $response->assertRedirect(route('frontend.settings.account'));
        $response->assertSessionHasErrors('email');
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertNotEquals($newEmail, $user->email);
    }
}
