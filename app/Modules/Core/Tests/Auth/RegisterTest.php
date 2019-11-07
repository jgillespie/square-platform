<?php

namespace App\Modules\Core\Tests\Auth;

use App\Modules\Core\Models\Account;
use App\Modules\Core\Tests\TestCase;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if (config('core.user_registration') !== true) {
            $this->markTestSkipped('User registration is disabled.');
        }
    }

    /** @test */
    public function user_can_view_a_registration_form()
    {
        $response = $this->get($this->registerRoute());

        $response->assertSuccessful();
        $response->assertViewIs('core::auth.register');
    }

    /** @test */
    public function user_cannot_view_a_registration_form_when_authenticated()
    {
        $user = factory(Account::class)->make();

        $response = $this->actingAs($user)->get($this->registerRoute());

        $this->assertRedirectHome($response, $user);
    }

    /** @test */
    public function user_can_register()
    {
        Event::fake();

        $response = $this->post($this->registerRoute(), [
            'email' => $email = 'john@example.com',
            'password' => $password = 'password',
            'password_confirmation' =>  $password,
        ]);

        $user = Account::all()->first();

        $this->assertRedirectHome($response, $user);
        $this->assertCount(1, Account::all());
        $this->assertAuthenticatedAs($user);
        $this->assertEquals($email, $user->email);
        $this->assertTrue(Hash::check($password, $user->password));
        Event::assertDispatched(Registered::class, function ($e) use ($user) {
            return $e->user->id === $user->id;
        });
    }

    /** @test */
    public function user_cannot_register_without_email()
    {
        $response = $this->from($this->registerRoute())->post($this->registerRoute(), [
            'email' => '',
            'password' => $password = 'password',
            'password_confirmation' =>  $password,
        ]);

        $this->assertCount(0, Account::all());
        $response->assertRedirect($this->registerRoute());
        $response->assertSessionHasErrors('email');
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    /** @test */
    public function user_cannot_register_with_invalid_email()
    {
        $response = $this->from($this->registerRoute())->post($this->registerRoute(), [
            'email' => 'invalid-email',
            'password' => $password = 'password',
            'password_confirmation' =>  $password,
        ]);

        $this->assertCount(0, Account::all());
        $response->assertRedirect($this->registerRoute());
        $response->assertSessionHasErrors('email');
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    /** @test */
    public function user_cannot_register_without_password()
    {
        $response = $this->from($this->registerRoute())->post($this->registerRoute(), [
            'email' => 'john@example.com',
            'password' => $emptyPassword = '',
            'password_confirmation' => $emptyPassword,
        ]);

        $this->assertCount(0, Account::all());
        $response->assertRedirect($this->registerRoute());
        $response->assertSessionHasErrors('password');
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    /** @test */
    public function user_cannot_register_without_password_confirmation()
    {
        $response = $this->from($this->registerRoute())->post($this->registerRoute(), [
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => '',
        ]);

        $this->assertCount(0, Account::all());
        $response->assertRedirect($this->registerRoute());
        $response->assertSessionHasErrors('password');
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    /** @test */
    public function user_cannot_register_with_passwords_not_matching()
    {
        $response = $this->from($this->registerRoute())->post($this->registerRoute(), [
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'another-password',
        ]);

        $this->assertCount(0, Account::all());
        $response->assertRedirect($this->registerRoute());
        $response->assertSessionHasErrors('password');
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }
}
