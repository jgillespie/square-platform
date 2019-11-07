<?php

namespace App\Modules\Core\Tests\Backend\Security\Account;

use App\Modules\Core\Models\Account;
use App\Modules\Core\Models\Role;
use App\Modules\Core\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_can_view_the_account_create_form()
    {
        $user = $this->backendUser(['backend.security.account.create']);

        $this->actingAs($user);
        $response = $this->get(route('backend.security.account.create'));

        $response->assertSuccessful();
        $response->assertViewIs('core::backend.security.account.create');
    }

    /** @test */
    public function users_can_create_an_account()
    {
        $data = [
            'is_enabled' => true,
            'is_backend' => true,
            'email' => 'john@example.com',
        ];

        $user = $this->backendUser(['backend.security.account.store']);

        $this->actingAs($user);
        $response = $this->post(route('backend.security.account.store'), array_merge($data, [
            'email_verified' => true,
        ]));

        $response->assertRedirect(route('backend.security.account.index'));
        $response->assertSessionHas('status');
        $this->assertDatabaseHas('accounts', $data);
    }

    /** @test */
    public function users_can_create_an_account_with_associated_roles()
    {
        $this->create(Role::class, [], 2);

        $data = [
            'is_enabled' => true,
            'is_backend' => true,
            'email' => $email = 'john@example.com',
        ];

        $user = $this->backendUser(['backend.security.account.store']);

        $this->actingAs($user);
        $response = $this->post(route('backend.security.account.store'), array_merge($data, [
            'email_verified' => true,
            'roles' => [1, 2],
        ]));

        $response->assertRedirect(route('backend.security.account.index'));
        $response->assertSessionHas('status');
        $this->assertDatabaseHas('accounts', $data);
        $this->assertCount(2, Account::where('email', $email)->first()->roles);
    }

    /** @test */
    public function users_may_not_create_an_account_with_invalid_associated_roles()
    {
        $user = $this->backendUser(['backend.security.account.store']);

        $this->actingAs($user);
        $this->from(route('backend.security.account.create'));
        $response = $this->post(route('backend.security.account.store'), [
            'is_enabled' => true,
            'is_backend' => true,
            'email' => 'john@example.com',
            'email_verified' => true,
            'roles' => [1, 2],
        ]);

        $response->assertRedirect(route('backend.security.account.create'));
        $response->assertSessionHasErrors('roles');
        $this->assertTrue(session()->hasOldInput('is_enabled'));
        $this->assertTrue(session()->hasOldInput('is_backend'));
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertTrue(session()->hasOldInput('email_verified'));
        $this->assertTrue(session()->hasOldInput('roles'));
    }

    /** @test */
    public function users_may_not_create_an_account_without_email()
    {
        $user = $this->backendUser(['backend.security.account.store']);

        $this->actingAs($user);
        $this->from(route('backend.security.account.create'));
        $response = $this->post(route('backend.security.account.store'));

        $response->assertRedirect(route('backend.security.account.create'));
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function users_may_not_create_an_account_with_invalid_email()
    {
        $user = $this->backendUser(['backend.security.account.store']);

        $this->actingAs($user);
        $this->from(route('backend.security.account.create'));
        $response = $this->post(route('backend.security.account.store'), [
            'email' => 'invalid-email',
        ]);

        $response->assertRedirect(route('backend.security.account.create'));
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function users_may_not_create_an_account_if_the_email_exists()
    {
        $account = $this->create(Account::class, [
            'email' => 'john@example.com',
        ]);

        $user = $this->backendUser(['backend.security.account.store']);

        $this->actingAs($user);
        $this->from(route('backend.security.account.create'));
        $response = $this->post(route('backend.security.account.store'), [
            'email' => $account->email,
        ]);

        $response->assertRedirect(route('backend.security.account.create'));
        $response->assertSessionHasErrors('email');
    }
}
