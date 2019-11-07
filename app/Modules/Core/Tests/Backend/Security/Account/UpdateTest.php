<?php

namespace App\Modules\Core\Tests\Backend\Security\Account;

use App\Modules\Core\Models\Account;
use App\Modules\Core\Models\Role;
use App\Modules\Core\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_can_view_the_account_update_form()
    {
        $account = $this->create(Account::class);

        $user = $this->backendUser(['backend.security.account.edit']);

        $this->actingAs($user);
        $response = $this->get(route('backend.security.account.edit', ['account' => $account->id]));

        $response->assertSuccessful();
        $response->assertViewIs('core::backend.security.account.edit');
    }

    /** @test */
    public function users_may_not_view_the_super_account_update_form()
    {
        $role = $this->create(Role::class, [
            'name' => 'super',
        ]);

        $account = $this->create(Account::class);

        $account->roles()->attach($role);

        $user = $this->backendUser(['backend.security.account.edit']);

        $this->actingAs($user);
        $this->from(route('backend.security.account.index'));
        $response = $this->get(route('backend.security.account.edit', ['account' => $account->id]));

        $response->assertRedirect(route('backend.security.account.index'));
        $response->assertSessionHasErrors('deny');
    }

    /** @test */
    public function users_can_update_an_account()
    {
        $account = $this->create(Account::class);

        $user = $this->backendUser(['backend.security.account.update']);

        $this->actingAs($user);
        $this->from(route('backend.security.account.edit', ['account' => $account->id]));
        $response = $this->patch(route('backend.security.account.update', ['account' => $account->id]), [
            'is_enabled' => $isEnabled = false,
            'is_backend' => $isBackend = false,
            'email' => $email = 'john@example.com',
        ]);

        $response->assertRedirect(route('backend.security.account.edit', ['account' => $account->id]));
        $response->assertSessionHas('status');
        $this->assertEquals($isEnabled, $account->fresh()->is_enabled);
        $this->assertEquals($isBackend, $account->fresh()->is_backend);
        $this->assertEquals($email, $account->fresh()->email);
        $this->assertNull($account->fresh()->email_verified_at);
    }

    /** @test */
    public function users_can_update_an_account_with_associated_roles()
    {
        $this->create(Role::class, [], 2);

        $account = $this->create(Account::class);

        $user = $this->backendUser(['backend.security.account.update']);

        $this->actingAs($user);
        $this->from(route('backend.security.account.edit', ['account' => $account->id]));
        $response = $this->patch(route('backend.security.account.update', ['account' => $account->id]), [
            'is_enabled' => $isEnabled = false,
            'is_backend' => $isBackend = false,
            'email' => $email = 'john@example.com',
            'roles' => [1, 2],
        ]);

        $response->assertRedirect(route('backend.security.account.edit', ['account' => $account->id]));
        $response->assertSessionHas('status');
        $this->assertEquals($isEnabled, $account->fresh()->is_enabled);
        $this->assertEquals($isBackend, $account->fresh()->is_backend);
        $this->assertEquals($email, $account->fresh()->email);
        $this->assertNull($account->fresh()->email_verified_at);
        $this->assertCount(2, $account->roles);
    }

    /** @test */
    public function users_may_not_update_the_super_account()
    {
        $role = $this->create(Role::class, [
            'name' => 'super',
        ]);

        $account = $this->create(Account::class);

        $account->roles()->attach($role);

        $user = $this->backendUser(['backend.security.account.update']);

        $this->actingAs($user);
        $this->from(route('backend.security.account.edit', ['account' => $account->id]));
        $response = $this->patch(route('backend.security.account.update', ['account' => $account->id]));

        $response->assertRedirect(route('backend.security.account.edit', ['account' => $account->id]));
        $response->assertSessionHasErrors('deny');
    }

    /** @test */
    public function users_may_not_update_an_account_with_invalid_associated_roles()
    {
        $account = $this->create(Account::class);

        $user = $this->backendUser(['backend.security.account.update']);

        $this->actingAs($user);
        $this->from(route('backend.security.account.edit', ['account' => $account->id]));
        $response = $this->patch(route('backend.security.account.update', ['account' => $account->id]), [
            'is_enabled' => $isEnabled = false,
            'is_backend' => $isBackend = false,
            'email' => $email = 'john@example.com',
            'roles' => [1, 2],
        ]);

        $response->assertRedirect(route('backend.security.account.edit', ['account' => $account->id]));
        $response->assertSessionHasErrors('roles');
        $this->assertTrue(session()->hasOldInput('is_enabled'));
        $this->assertTrue(session()->hasOldInput('is_backend'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertTrue(session()->hasOldInput('roles'));
    }

    /** @test */
    public function users_may_not_update_an_account_without_email()
    {
        $account = $this->create(Account::class);

        $user = $this->backendUser(['backend.security.account.update']);

        $this->actingAs($user);
        $this->from(route('backend.security.account.edit', ['account' => $account->id]));
        $response = $this->patch(route('backend.security.account.update', ['account' => $account->id]));

        $response->assertRedirect(route('backend.security.account.edit', ['account' => $account->id]));
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function users_may_not_update_an_account_with_empty_email()
    {
        $account = $this->create(Account::class);

        $user = $this->backendUser(['backend.security.account.update']);

        $this->actingAs($user);
        $this->from(route('backend.security.account.edit', ['account' => $account->id]));
        $response = $this->patch(route('backend.security.account.update', ['account' => $account->id]), [
            'email' => '',
        ]);

        $response->assertRedirect(route('backend.security.account.edit', ['account' => $account->id]));
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function users_may_not_update_an_account_if_the_email_exists()
    {
        $oldAccount = $this->create(Account::class, [
            'email' => 'john@example.com',
        ]);

        $account = $this->create(Account::class);

        $user = $this->backendUser(['backend.security.account.update']);

        $this->actingAs($user);
        $this->from(route('backend.security.account.edit', ['account' => $account->id]));
        $response = $this->patch(route('backend.security.account.update', ['account' => $account->id]), [
            'email' => $oldAccount->email,
        ]);

        $response->assertRedirect(route('backend.security.account.edit', ['account' => $account->id]));
        $response->assertSessionHasErrors('email');
        $this->assertTrue(session()->hasOldInput('email'));
    }
}
