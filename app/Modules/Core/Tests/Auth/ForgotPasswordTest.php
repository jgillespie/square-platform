<?php

namespace App\Modules\Core\Tests\Auth;

use App\Modules\Core\Models\Account;
use App\Modules\Core\Tests\TestCase;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_view_an_email_password_form()
    {
        $response = $this->get($this->passwordRequestRoute());

        $response->assertSuccessful();
        $response->assertViewIs('core::auth.passwords.email');
    }

    /** @test */
    public function user_cannot_view_an_email_password_form_when_authenticated()
    {
        $user = factory(Account::class)->make();

        $response = $this->actingAs($user)->get($this->passwordRequestRoute());

        $this->assertRedirectHome($response, $user);
    }

    /** @test */
    public function user_receives_an_email_with_a_password_reset_link()
    {
        Notification::fake();
        $user = factory(Account::class)->create();

        $this->post($this->passwordEmailRoute(), [
            'email' => $user->email,
        ]);

        $accountPasswordReset = DB::table('account_password_resets')->first();

        $this->assertNotNull($accountPasswordReset);
        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($accountPasswordReset) {
            return Hash::check($notification->token, $accountPasswordReset->token) === true;
        });
    }

    /** @test */
    public function user_does_not_receive_email_when_not_registered()
    {
        Notification::fake();
        $user = factory(Account::class)->make();

        $response = $this->from($this->passwordRequestRoute())->post($this->passwordEmailRoute(), [
            'email' => $user->email,
        ]);

        $response->assertRedirect($this->passwordRequestRoute());
        $response->assertSessionHasErrors('email');
        Notification::assertNotSentTo($user, ResetPassword::class);
    }

    /** @test */
    public function email_is_required()
    {
        $response = $this->from($this->passwordRequestRoute())->post($this->passwordEmailRoute(), []);

        $response->assertRedirect($this->passwordRequestRoute());
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function email_is_a_valid_email()
    {
        $response = $this->from($this->passwordRequestRoute())->post($this->passwordEmailRoute(), [
            'email' => 'invalid-email',
        ]);

        $response->assertRedirect($this->passwordRequestRoute());
        $response->assertSessionHasErrors('email');
    }
}
