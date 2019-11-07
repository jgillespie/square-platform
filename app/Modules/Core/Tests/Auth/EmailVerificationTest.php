<?php

namespace App\Modules\Core\Tests\Auth;

use App\Modules\Core\Models\Account;
use App\Modules\Core\Tests\TestCase;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_see_the_verification_notice()
    {
        $response = $this->get($this->verificationNoticeRoute());

        $response->assertRedirect($this->loginRoute());
    }

    /** @test */
    public function user_sees_the_verification_notice_when_not_verified()
    {
        $user = factory(Account::class)->make([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->get($this->verificationNoticeRoute());

        $response->assertStatus(200);
        $response->assertViewIs('core::auth.verify');
    }

    /** @test */
    public function verified_user_is_redirected_home_when_visiting_verification_notice_route()
    {
        $user = factory(Account::class)->make();

        $response = $this->actingAs($user)->get($this->verificationNoticeRoute());

        $this->assertRedirectHome($response, $user);
    }

    /** @test */
    public function guest_cannot_see_the_verification_verify_route()
    {
        $user = factory(Account::class)->create();

        $response = $this->get($this->verificationVerifyRoute($user));

        $response->assertRedirect($this->loginRoute());
    }

    /** @test */
    public function user_cannot_verify_others()
    {
        $user1 = factory(Account::class)->create([
            'email_verified_at' => null,
        ]);

        $user2 = factory(Account::class)->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user1)->get($this->verificationVerifyRoute($user2));

        $response->assertForbidden();
        $this->assertFalse($user2->fresh()->hasVerifiedEmail());
    }

    /** @test */
    public function user_is_redirected_to_correct_route_when_already_verified()
    {
        $user = factory(Account::class)->create();

        $response = $this->actingAs($user)->get($this->verificationVerifyRoute($user));

        $this->assertRedirectHome($response, $user);
    }

    /** @test */
    public function forbidden_is_returned_when_signature_is_invalid_in_verification_verfy_route()
    {
        $user = factory(Account::class)->create();

        $response = $this->actingAs($user)->get($this->verificationVerifyRoute($user).'?signature=invalid-signature');

        $response->assertStatus(403);
    }

    /** @test */
    public function user_can_verify_themselves()
    {
        $user = factory(Account::class)->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->get($this->verificationVerifyRoute($user));

        $this->assertRedirectHome($response, $user);
        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    /** @test */
    public function guest_cannot_resend_a_verification_email()
    {
        $response = $this->post($this->verificationResendRoute());

        $response->assertRedirect($this->loginRoute());
    }

    /** @test */
    public function user_is_redirected_to_correct_route_if_already_verified()
    {
        $user = factory(Account::class)->make();

        $response = $this->actingAs($user)->post($this->verificationResendRoute());

        $this->assertRedirectHome($response, $user);
    }

    /** @test */
    public function user_can_resend_a_verification_email()
    {
        Notification::fake();
        $user = factory(Account::class)->make([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)
            ->from($this->verificationNoticeRoute())
            ->post($this->verificationResendRoute());

        Notification::assertSentTo($user, VerifyEmail::class);
        $response->assertRedirect($this->verificationNoticeRoute());
    }
}
