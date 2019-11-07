<?php

namespace App\Modules\Core\Tests\Backend\Security\Role;

use App\Modules\Core\Models\Permission;
use App\Modules\Core\Models\Role;
use App\Modules\Core\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_can_view_the_role_create_form()
    {
        $user = $this->backendUser(['backend.security.role.create']);

        $this->actingAs($user);
        $response = $this->get(route('backend.security.role.create'));

        $response->assertSuccessful();
        $response->assertViewIs('core::backend.security.role.create');
    }

    /** @test */
    public function users_can_create_a_role()
    {
        $data = [
            'name' => 'role-name',
            'description' => 'role-description',
        ];

        $user = $this->backendUser(['backend.security.role.store']);

        $this->actingAs($user);
        $response = $this->post(route('backend.security.role.store'), $data);

        $response->assertRedirect(route('backend.security.role.index'));
        $response->assertSessionHas('status');
        $this->assertDatabaseHas('roles', $data);
    }

    /** @test */
    public function users_can_create_a_role_with_associated_permissions()
    {
        $this->create(Permission::class, [], 2);

        $data = [
            'name' => $name = 'role-name',
            'description' => 'role-description',
        ];

        $user = $this->backendUser(['backend.security.role.store']);

        $this->actingAs($user);
        $response = $this->post(route('backend.security.role.store'), array_merge($data, [
            'permissions' => [1, 2],
        ]));

        $response->assertRedirect(route('backend.security.role.index'));
        $response->assertSessionHas('status');
        $this->assertDatabaseHas('roles', $data);
        $this->assertCount(2, Role::where('name', $name)->first()->permissions);
    }

    /** @test */
    public function users_may_not_create_a_role_with_invalid_associated_permissions()
    {
        $user = $this->backendUser(['backend.security.role.store']);

        $this->actingAs($user);
        $this->from(route('backend.security.role.create'));
        $response = $this->post(route('backend.security.role.store'), [
            'name' => 'role-name',
            'description' => 'role-description',
            'permissions' => [1, 2],
        ]);

        $response->assertRedirect(route('backend.security.role.create'));
        $response->assertSessionHasErrors('permissions');
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('description'));
        $this->assertTrue(session()->hasOldInput('permissions'));
    }

    /** @test */
    public function users_may_not_create_a_role_without_name()
    {
        $user = $this->backendUser(['backend.security.role.store']);

        $this->actingAs($user);
        $this->from(route('backend.security.role.create'));
        $response = $this->post(route('backend.security.role.store'), [
            'description' => 'role-description',
        ]);

        $response->assertRedirect(route('backend.security.role.create'));
        $response->assertSessionHasErrors('name');
        $this->assertTrue(session()->hasOldInput('description'));
    }

    /** @test */
    public function users_may_not_create_a_role_with_empty_name()
    {
        $user = $this->backendUser(['backend.security.role.store']);

        $this->actingAs($user);
        $this->from(route('backend.security.role.create'));
        $response = $this->post(route('backend.security.role.store'), [
            'name' => '',
            'description' => 'role-description',
        ]);

        $response->assertRedirect(route('backend.security.role.create'));
        $response->assertSessionHasErrors('name');
        $this->assertTrue(session()->hasOldInput('description'));
    }

    /** @test */
    public function users_may_not_create_a_role_if_the_name_exists()
    {
        $role = $this->create(Role::class, [
            'name' => 'role-name',
        ]);

        $user = $this->backendUser(['backend.security.role.store']);

        $this->actingAs($user);
        $this->from(route('backend.security.role.create'));
        $response = $this->post(route('backend.security.role.store'), [
            'name' => $role->name,
            'description' => 'role-description',
        ]);

        $response->assertRedirect(route('backend.security.role.create'));
        $response->assertSessionHasErrors('name');
        $this->assertTrue(session()->hasOldInput('name'));
        $this->assertTrue(session()->hasOldInput('description'));
    }

    /** @test */
    public function users_may_not_create_a_role_without_description()
    {
        $user = $this->backendUser(['backend.security.role.store']);

        $this->actingAs($user);
        $this->from(route('backend.security.role.create'));
        $response = $this->post(route('backend.security.role.store'), [
            'name' => 'role-name',
        ]);

        $response->assertRedirect(route('backend.security.role.create'));
        $response->assertSessionHasErrors('description');
        $this->assertTrue(session()->hasOldInput('name'));
    }

    /** @test */
    public function users_may_not_create_a_role_with_empty_description()
    {
        $user = $this->backendUser(['backend.security.role.store']);

        $this->actingAs($user);
        $this->from(route('backend.security.role.create'));
        $response = $this->post(route('backend.security.role.store'), [
            'name' => 'role-name',
            'description' => '',
        ]);

        $response->assertRedirect(route('backend.security.role.create'));
        $response->assertSessionHasErrors('description');
        $this->assertTrue(session()->hasOldInput('name'));
    }
}
