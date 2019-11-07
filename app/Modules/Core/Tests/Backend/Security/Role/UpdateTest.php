<?php

namespace App\Modules\Core\Tests\Backend\Security\Role;

use App\Modules\Core\Models\Permission;
use App\Modules\Core\Models\Role;
use App\Modules\Core\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_can_view_the_role_update_form()
    {
        $role = $this->create(Role::class);

        $user = $this->backendUser(['backend.security.role.edit']);

        $this->actingAs($user);
        $response = $this->get(route('backend.security.role.edit', ['role' => $role->id]));

        $response->assertSuccessful();
        $response->assertViewIs('core::backend.security.role.edit');
    }

    /** @test */
    public function users_may_not_view_the_super_role_update_form()
    {
        $role = $this->create(Role::class, [
            'name' => 'super',
        ]);

        $user = $this->backendUser(['backend.security.role.edit']);

        $this->actingAs($user);
        $this->from(route('backend.security.role.index'));
        $response = $this->get(route('backend.security.role.edit', ['role' => $role->id]));

        $response->assertRedirect(route('backend.security.role.index'));
        $response->assertSessionHasErrors('deny');
    }

    /** @test */
    public function users_can_update_a_role()
    {
        $role = $this->create(Role::class);

        $user = $this->backendUser(['backend.security.role.update']);

        $this->actingAs($user);
        $this->from(route('backend.security.role.edit', ['role' => $role->id]));
        $response = $this->patch(route('backend.security.role.update', ['role' => $role->id]), [
            'name' => $name = 'role-name',
            'description' => $description = 'role-description',
        ]);

        $response->assertRedirect(route('backend.security.role.edit', ['role' => $role->id]));
        $response->assertSessionHas('status');
        $this->assertEquals($name, $role->fresh()->name);
        $this->assertEquals($description, $role->fresh()->description);
    }

    /** @test */
    public function users_can_update_a_role_with_associated_permissions()
    {
        $this->create(Permission::class, [], 2);

        $role = $this->create(Role::class);

        $user = $this->backendUser(['backend.security.role.update']);

        $this->actingAs($user);
        $this->from(route('backend.security.role.edit', ['role' => $role->id]));
        $response = $this->patch(route('backend.security.role.update', ['role' => $role->id]), [
            'name' => $name = 'role-name',
            'description' => $description = 'role-description',
            'permissions' => [1, 2],
        ]);

        $response->assertRedirect(route('backend.security.role.edit', ['role' => $role->id]));
        $response->assertSessionHas('status');
        $this->assertEquals($name, $role->fresh()->name);
        $this->assertEquals($description, $role->fresh()->description);
        $this->assertCount(2, $role->permissions);
    }

    /** @test */
    public function users_may_not_update_the_super_role()
    {
        $role = $this->create(Role::class, [
            'name' => 'super',
        ]);

        $user = $this->backendUser(['backend.security.role.update']);

        $this->actingAs($user);
        $this->from(route('backend.security.role.edit', ['role' => $role->id]));
        $response = $this->patch(route('backend.security.role.update', ['role' => $role->id]));

        $response->assertRedirect(route('backend.security.role.edit', ['role' => $role->id]));
        $response->assertSessionHasErrors('deny');
    }

    /** @test */
    public function users_may_not_update_a_role_with_invalid_associated_permissions()
    {
        $role = $this->create(Role::class);

        $user = $this->backendUser(['backend.security.role.update']);

        $this->actingAs($user);
        $this->from(route('backend.security.role.edit', ['role' => $role->id]));
        $response = $this->patch(route('backend.security.role.update', ['role' => $role->id]), [
            'name' => 'role-name',
            'description' => 'role-description',
            'permissions' => [1, 2],
        ]);

        $response->assertRedirect(route('backend.security.role.edit', ['role' => $role->id]));
        $response->assertSessionHasErrors('permissions');
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('description'));
        $this->assertTrue(session()->hasOldInput('permissions'));
    }

    /** @test */
    public function users_may_not_update_a_role_without_name()
    {
        $role = $this->create(Role::class);

        $user = $this->backendUser(['backend.security.role.update']);

        $this->actingAs($user);
        $this->from(route('backend.security.role.edit', ['role' => $role->id]));
        $response = $this->patch(route('backend.security.role.update', ['role' => $role->id]), [
            'description' => 'role-description',
        ]);

        $response->assertRedirect(route('backend.security.role.edit', ['role' => $role->id]));
        $response->assertSessionHasErrors('name');
        $this->assertTrue(session()->hasOldInput('description'));
    }

    /** @test */
    public function users_may_not_update_a_role_with_empty_name()
    {
        $role = $this->create(Role::class);

        $user = $this->backendUser(['backend.security.role.update']);

        $this->actingAs($user);
        $this->from(route('backend.security.role.edit', ['role' => $role->id]));
        $response = $this->patch(route('backend.security.role.update', ['role' => $role->id]), [
            'name' => '',
            'description' => 'role-description',
        ]);

        $response->assertRedirect(route('backend.security.role.edit', ['role' => $role->id]));
        $response->assertSessionHasErrors('name');
        $this->assertTrue(session()->hasOldInput('description'));
    }

    /** @test */
    public function users_may_not_update_a_role_if_the_name_exists()
    {
        $oldRole = $this->create(Role::class, [
            'name' => 'role-name',
            'name' => 'role-description',
        ]);

        $role = $this->create(Role::class);

        $user = $this->backendUser(['backend.security.role.update']);

        $this->actingAs($user);
        $this->from(route('backend.security.role.edit', ['role' => $role->id]));
        $response = $this->patch(route('backend.security.role.update', ['role' => $role->id]), [
            'name' => $oldRole->name,
            'description' => 'role-description',
        ]);

        $response->assertRedirect(route('backend.security.role.edit', ['role' => $role->id]));
        $response->assertSessionHasErrors('name');
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('description'));
    }

    /** @test */
    public function users_may_not_update_a_role_without_description()
    {
        $role = $this->create(Role::class);

        $user = $this->backendUser(['backend.security.role.update']);

        $this->actingAs($user);
        $this->from(route('backend.security.role.edit', ['role' => $role->id]));
        $response = $this->patch(route('backend.security.role.update', ['role' => $role->id]), [
            'name' => 'role-name',
        ]);

        $response->assertRedirect(route('backend.security.role.edit', ['role' => $role->id]));
        $response->assertSessionHasErrors('description');
        $this->assertTrue(session()->hasOldInput('name'));
    }

    /** @test */
    public function users_may_not_update_a_role_with_empty_description()
    {
        $role = $this->create(Role::class);

        $user = $this->backendUser(['backend.security.role.update']);

        $this->actingAs($user);
        $this->from(route('backend.security.role.edit', ['role' => $role->id]));
        $response = $this->patch(route('backend.security.role.update', ['role' => $role->id]), [
            'name' => 'role-name',
            'description' => '',
        ]);

        $response->assertRedirect(route('backend.security.role.edit', ['role' => $role->id]));
        $response->assertSessionHasErrors('description');
        $this->assertTrue(session()->hasOldInput('name'));
    }
}
