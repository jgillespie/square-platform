<?php

namespace App\Modules\Core\Tests\Auth;

use App\Modules\Core\Models\Account;
use App\Modules\Core\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_view_a_login_form()
    {
        $response = $this->get($this->loginRoute());

        $response->assertSuccessful();
        $response->assertViewIs('core::auth.login');
    }

    /** @test */
    public function user_cannot_view_a_login_form_when_authenticated()
    {
        $user = factory(Account::class)->make();

        $response = $this->actingAs($user)->get($this->loginRoute());

        $this->assertRedirectHome($response, $user);
    }

    /** @test */
    public function user_can_login_with_correct_credentials()
    {
        $user = factory(Account::class)->create();

        $response = $this->post($this->loginRoute(), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertRedirectHome($response, $user);
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function remember_me_functionality()
    {
        $user = factory(Account::class)->create([
            'id' => random_int(1, 100),
        ]);

        $response = $this->post($this->loginRoute(), [
            'email' => $user->email,
            'password' => 'password',
            'remember' => 'on',
        ]);

        $user = $user->fresh();

        $this->assertRedirectHome($response, $user);
        $response->assertCookie(Auth::guard()->getRecallerName(), vsprintf('%s|%s|%s', [
            $user->id,
            $user->getRememberToken(),
            $user->password,
        ]));
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function user_cannot_login_with_incorrect_password()
    {
        $user = factory(Account::class)->create();

        $response = $this->from($this->loginRoute())->post($this->loginRoute(), [
            'email' => $user->email,
            'password' => 'invalid-password',
        ]);

        $response->assertRedirect($this->loginRoute());
        $response->assertSessionHasErrors('email');
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    /** @test */
    public function user_cannot_login_with_email_that_does_not_exist()
    {
        $response = $this->from($this->loginRoute())->post($this->loginRoute(), [
            'email' => 'nobody@example.com',
            'password' => 'invalid-password',
        ]);

        $response->assertRedirect($this->loginRoute());
        $response->assertSessionHasErrors('email');
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    /** @test */
    public function user_can_logout()
    {
        $user = factory(Account::class)->make();

        $response = $this->actingAs($user)->post($this->logoutRoute());

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    /** @test */
    public function user_cannot_logout_when_not_authenticated()
    {
        $response = $this->post($this->logoutRoute());

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    /** @test */
    public function user_cannot_make_more_than_five_attempts_in_one_minute()
    {
        foreach (range(0, 5) as $_) {
            $response = $this->from($this->loginRoute())->post($this->loginRoute(), [
                'email' => 'nobody@example.com',
                'password' => 'invalid-password',
            ]);
        }

        $response->assertRedirect($this->loginRoute());
        $response->assertSessionHasErrors('email');
        $this->assertRegExp(
            sprintf('/^%s$/', str_replace('\:seconds', '\d+', preg_quote(__('auth.throttle'), '/'))),
            collect(
                $response
                ->baseResponse
                ->getSession()
                ->get('errors')
                ->getBag('default')
                ->get('email')
            )->first()
        );
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }
}
