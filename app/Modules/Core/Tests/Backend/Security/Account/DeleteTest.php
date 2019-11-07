<?php

namespace App\Modules\Core\Tests\Backend\Security\Account;

use App\Modules\Core\Models\Account;
use App\Modules\Core\Models\Role;
use App\Modules\Core\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_may_not_delete_an_account()
    {
        $account = $this->create(Account::class);

        $response = $this->delete(route('backend.security.account.destroy', ['account' => $account->id]));

        $response->assertRedirect($this->loginRoute());
    }

    /** @test */
    public function disabled_users_may_not_delete_an_account()
    {
        $account = $this->create(Account::class);

        $user = $this->make(Account::class, [
            'is_enabled' => false,
        ]);

        $this->actingAs($user);
        $response = $this->delete(route('backend.security.account.destroy', ['account' => $account->id]));

        $response->assertRedirect($this->loginRoute());
    }

    /** @test */
    public function enabled_frontend_users_may_not_delete_an_account()
    {
        $account = $this->create(Account::class);

        $user = $this->make(Account::class, [
            'is_enabled' => true,
            'is_backend' => false,
        ]);

        $this->actingAs($user);
        $response = $this->delete(route('backend.security.account.destroy', ['account' => $account->id]));

        $response->assertRedirect(config('core.frontend_routes_prefix'));
    }

    /** @test */
    public function enabled_unverified_backend_users_may_not_delete_an_account()
    {
        $account = $this->create(Account::class);

        $user = $this->make(Account::class, [
            'is_enabled' => true,
            'is_backend' => true,
            'email_verified_at' => null,
        ]);

        $this->actingAs($user);
        $response = $this->delete(route('backend.security.account.destroy', ['account' => $account->id]));

        $response->assertRedirect($this->verificationNoticeRoute());
    }

    /** @test */
    public function enabled_verified_backend_users_without_permission_may_not_delete_an_account()
    {
        $account = $this->create(Account::class);

        $user = $this->make(Account::class, [
            'is_enabled' => true,
            'is_backend' => true,
        ]);

        $this->actingAs($user);
        $this->from(route('backend.security.account.index'));
        $response = $this->delete(route('backend.security.account.destroy', ['account' => $account->id]));

        $response->assertRedirect(route('backend.security.account.index'));
        $response->assertSessionHasErrors('permission');
    }

    /** @test */
    public function enabled_verified_backend_users_with_permission_can_delete_an_account()
    {
        $account = $this->create(Account::class);

        $user = $this->backendUser(['backend.security.account.destroy']);

        $this->actingAs($user);
        $this->from(route('backend.security.account.index'));
        $response = $this->delete(route('backend.security.account.destroy', ['account' => $account->id]));

        $response->assertRedirect(route('backend.security.account.index'));
        $response->assertSessionHas('status');
        $this->assertDatabaseMissing('accounts', $account->toArray());
    }

    /** @test */
    public function users_may_not_delete_the_super_account()
    {
        $role = $this->create(Role::class, [
            'name' => 'super',
        ]);

        $account = $this->create(Account::class);

        $account->roles()->attach($role);

        $user = $this->backendUser(['backend.security.account.destroy']);

        $this->actingAs($user);
        $this->from(route('backend.security.account.index'));
        $response = $this->delete(route('backend.security.account.destroy', ['account' => $account->id]));

        $response->assertRedirect(route('backend.security.account.index'));
        $response->assertSessionHasErrors('deny');
        $this->assertDatabaseHas('accounts', $account->toArray());
    }
}
