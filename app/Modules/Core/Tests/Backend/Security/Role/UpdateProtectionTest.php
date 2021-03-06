<?php

namespace App\Modules\Core\Tests\Backend\Security\Role;

use App\Modules\Core\Models\Account;
use App\Modules\Core\Models\Role;
use App\Modules\Core\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateProtectionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_may_not_view_the_role_update_form()
    {
        $role = $this->create(Role::class);

        $response = $this->get(route('backend.security.role.edit', ['role' => $role->id]));

        $response->assertRedirect($this->loginRoute());
    }

    /** @test */
    public function disabled_users_may_not_view_the_role_update_form()
    {
        $role = $this->create(Role::class);

        $user = $this->make(Account::class, [
            'is_enabled' => false,
        ]);

        $this->actingAs($user);
        $response = $this->get(route('backend.security.role.edit', ['role' => $role->id]));

        $response->assertRedirect($this->loginRoute());
    }

    /** @test */
    public function enabled_frontend_users_may_not_view_the_role_update_form()
    {
        $role = $this->create(Role::class);

        $user = $this->make(Account::class, [
            'is_enabled' => true,
            'is_backend' => false,
        ]);

        $this->actingAs($user);
        $response = $this->get(route('backend.security.role.edit', ['role' => $role->id]));

        $response->assertRedirect(config('core.frontend_routes_prefix'));
    }

    /** @test */
    public function enabled_unverified_backend_users_may_not_view_the_role_update_form()
    {
        $role = $this->create(Role::class);

        $user = $this->make(Account::class, [
            'is_enabled' => true,
            'is_backend' => true,
            'email_verified_at' => null,
        ]);

        $this->actingAs($user);
        $response = $this->get(route('backend.security.role.edit', ['role' => $role->id]));

        $response->assertRedirect($this->verificationNoticeRoute());
    }

    /** @test */
    public function enabled_verified_backend_users_without_permission_may_not_view_the_role_update_form()
    {
        $role = $this->create(Role::class);

        $user = $this->make(Account::class, [
            'is_enabled' => true,
            'is_backend' => true,
        ]);

        $this->actingAs($user);
        $this->from(config('core.backend_routes_prefix'));
        $response = $this->get(route('backend.security.role.edit', ['role' => $role->id]));

        $response->assertRedirect(config('core.backend_routes_prefix'));
        $response->assertSessionHasErrors('permission');
    }

    /** @test */
    public function guests_may_not_update_a_role()
    {
        $role = $this->create(Role::class);

        $response = $this->patch(route('backend.security.role.update', ['role' => $role->id]));

        $response->assertRedirect($this->loginRoute());
    }

    /** @test */
    public function disabled_users_may_not_update_a_role()
    {
        $role = $this->create(Role::class);

        $user = $this->make(Account::class, [
            'is_enabled' => false,
        ]);

        $this->actingAs($user);
        $response = $this->patch(route('backend.security.role.update', ['role' => $role->id]));

        $response->assertRedirect($this->loginRoute());
    }

    /** @test */
    public function enabled_frontend_users_may_not_update_a_role()
    {
        $role = $this->create(Role::class);

        $user = $this->make(Account::class, [
            'is_enabled' => true,
            'is_backend' => false,
        ]);

        $this->actingAs($user);
        $response = $this->patch(route('backend.security.role.update', ['role' => $role->id]));

        $response->assertRedirect(config('core.frontend_routes_prefix'));
    }

    /** @test */
    public function enabled_unverified_backend_users_may_not_update_a_role()
    {
        $role = $this->create(Role::class);

        $user = $this->make(Account::class, [
            'is_enabled' => true,
            'is_backend' => true,
            'email_verified_at' => null,
        ]);

        $this->actingAs($user);
        $response = $this->patch(route('backend.security.role.update', ['role' => $role->id]));

        $response->assertRedirect($this->verificationNoticeRoute());
    }

    /** @test */
    public function enabled_verified_backend_users_without_permission_may_not_update_a_role()
    {
        $role = $this->create(Role::class);

        $user = $this->make(Account::class, [
            'is_enabled' => true,
            'is_backend' => true,
        ]);

        $this->actingAs($user);
        $this->from(route('backend.security.role.edit', ['role' => $role->id]));
        $response = $this->patch(route('backend.security.role.update', ['role' => $role->id]));

        $response->assertRedirect(route('backend.security.role.edit', ['role' => $role->id]));
        $response->assertSessionHasErrors('permission');
    }
}
