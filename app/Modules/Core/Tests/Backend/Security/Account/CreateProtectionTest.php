<?php

namespace App\Modules\Core\Tests\Backend\Security\Account;

use App\Modules\Core\Models\Account;
use App\Modules\Core\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateProtectionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_may_not_view_the_account_create_form()
    {
        $response = $this->get(route('backend.security.account.create'));

        $response->assertRedirect($this->loginRoute());
    }

    /** @test */
    public function disabled_users_may_not_view_the_account_create_form()
    {
        $user = $this->make(Account::class, [
            'is_enabled' => false,
        ]);

        $this->actingAs($user);
        $response = $this->get(route('backend.security.account.create'));

        $response->assertRedirect($this->loginRoute());
    }

    /** @test */
    public function enabled_frontend_users_may_not_view_the_account_create_form()
    {
        $user = $this->make(Account::class, [
            'is_enabled' => true,
            'is_backend' => false,
        ]);

        $this->actingAs($user);
        $response = $this->get(route('backend.security.account.create'));

        $response->assertRedirect(config('core.frontend_routes_prefix'));
    }

    /** @test */
    public function enabled_unverified_backend_users_may_not_view_the_account_create_form()
    {
        $user = $this->make(Account::class, [
            'is_enabled' => true,
            'is_backend' => true,
            'email_verified_at' => null,
        ]);

        $this->actingAs($user);
        $response = $this->get(route('backend.security.account.create'));

        $response->assertRedirect($this->verificationNoticeRoute());
    }

    /** @test */
    public function enabled_verified_backend_users_without_permission_may_not_view_the_account_create_form()
    {
        $user = $this->make(Account::class, [
            'is_enabled' => true,
            'is_backend' => true,
        ]);

        $this->actingAs($user);
        $this->from(config('core.backend_routes_prefix'));
        $response = $this->get(route('backend.security.account.create'));

        $response->assertRedirect(config('core.backend_routes_prefix'));
        $response->assertSessionHasErrors('permission');
    }

    /** @test */
    public function guests_may_not_create_an_account()
    {
        $response = $this->post(route('backend.security.account.store'));

        $response->assertRedirect($this->loginRoute());
    }

    /** @test */
    public function disabled_users_may_not_create_an_account()
    {
        $user = $this->make(Account::class, [
            'is_enabled' => false,
        ]);

        $this->actingAs($user);
        $response = $this->post(route('backend.security.account.store'));

        $response->assertRedirect($this->loginRoute());
    }

    /** @test */
    public function enabled_frontend_users_may_not_create_an_account()
    {
        $user = $this->make(Account::class, [
            'is_enabled' => true,
            'is_backend' => false,
        ]);

        $this->actingAs($user);
        $response = $this->post(route('backend.security.account.store'));

        $response->assertRedirect(config('core.frontend_routes_prefix'));
    }

    /** @test */
    public function enabled_unverified_backend_users_may_not_create_an_account()
    {
        $user = $this->make(Account::class, [
            'is_enabled' => true,
            'is_backend' => true,
            'email_verified_at' => null,
        ]);

        $this->actingAs($user);
        $response = $this->post(route('backend.security.account.store'));

        $response->assertRedirect($this->verificationNoticeRoute());
    }

    /** @test */
    public function enabled_verified_backend_users_without_permission_may_not_create_an_account()
    {
        $user = $this->make(Account::class, [
            'is_enabled' => true,
            'is_backend' => true,
        ]);

        $this->actingAs($user);
        $this->from(route('backend.security.account.create'));
        $response = $this->post(route('backend.security.account.store'));

        $response->assertRedirect(route('backend.security.account.create'));
        $response->assertSessionHasErrors('permission');
    }
}
