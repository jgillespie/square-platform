<?php

namespace App\Modules\Core\Tests\Auth;

use App\Modules\Core\Models\Account;
use App\Modules\Core\Tests\TestCase;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    protected function getInvalidToken()
    {
        return 'invalid-token';
    }

    protected function getValidToken($user)
    {
        return Password::broker()->createToken($user);
    }

    /** @test */
    public function user_can_view_a_password_reset_form()
    {
        $token = $this->getInvalidToken();

        $response = $this->get($this->passwordResetRoute($token));

        $response->assertSuccessful();
        $response->assertViewIs('core::auth.passwords.reset');
        $response->assertViewHas('token', $token);
    }

    /** @test */
    public function user_cannot_view_a_password_reset_form_when_authenticated()
    {
        $user = factory(Account::class)->make();

        $response = $this->actingAs($user)->get($this->passwordResetRoute($this->getInvalidToken()));

        $this->assertRedirectHome($response, $user);
    }

    /** @test */
    public function user_can_reset_password_with_valid_token()
    {
        Event::fake();
        $user = factory(Account::class)->create();

        $response = $this->post($this->passwordUpdateRoute(), [
            'token' => $this->getValidToken($user),
            'email' => $user->email,
            'password' => $newPassword = 'new-password',
            'password_confirmation' => $newPassword,
        ]);

        $this->assertRedirectHome($response, $user);
        $this->assertEquals($user->email, $user->fresh()->email);
        $this->assertTrue(Hash::check($newPassword, $user->fresh()->password));
        $this->assertAuthenticatedAs($user);
        Event::assertDispatched(PasswordReset::class, function ($e) use ($user) {
            return $e->user->id === $user->id;
        });
    }

    /** @test */
    public function user_cannot_reset_password_with_invalid_token()
    {
        $user = factory(Account::class)->create();
        $token = $this->getInvalidToken();

        $response = $this->from($this->passwordResetRoute($token))->post($this->passwordUpdateRoute(), [
            'token' => $token,
            'email' => $user->email,
            'password' => $newPassword = 'new-password',
            'password_confirmation' => $newPassword,
        ]);

        $response->assertRedirect($this->passwordResetRoute($token));
        $this->assertEquals($user->email, $user->fresh()->email);
        $this->assertFalse(Hash::check($newPassword, $user->fresh()->password));
        $this->assertGuest();
    }

    /** @test */
    public function user_cannot_reset_password_without_providing_a_new_password()
    {
        $user = factory(Account::class)->create();
        $token = $this->getValidToken($user);

        $response = $this->from($this->passwordResetRoute($token))->post($this->passwordUpdateRoute(), [
            'token' => $token,
            'email' => $user->email,
            'password' => $emptyPassword = '',
            'password_confirmation' => $emptyPassword,
        ]);

        $response->assertRedirect($this->passwordResetRoute($token));
        $response->assertSessionHasErrors('password');
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertEquals($user->email, $user->fresh()->email);
        $this->assertFalse(Hash::check($emptyPassword, $user->fresh()->password));
        $this->assertGuest();
    }

    /** @test */
    public function user_cannot_reset_password_without_providing_an_email()
    {
        $user = factory(Account::class)->create();
        $token = $this->getValidToken($user);

        $response = $this->from($this->passwordResetRoute($token))->post($this->passwordUpdateRoute(), [
            'token' => $token,
            'email' => '',
            'password' => $newPassword = 'new-password',
            'password_confirmation' => $newPassword,
        ]);

        $response->assertRedirect($this->passwordResetRoute($token));
        $response->assertSessionHasErrors('email');
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertEquals($user->email, $user->fresh()->email);
        $this->assertFalse(Hash::check($newPassword, $user->fresh()->password));
        $this->assertGuest();
    }
}
