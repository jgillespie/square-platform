<?php

namespace App\Modules\Core\Tests\Backend\Security\Permission;

use App\Modules\Core\Models\Account;
use App\Modules\Core\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_may_not_view_the_permission_index()
    {
        $response = $this->get(route('backend.security.permission.index'));

        $response->assertRedirect($this->loginRoute());
    }

    /** @test */
    public function disabled_users_may_not_view_the_permission_index()
    {
        $user = $this->make(Account::class, [
            'is_enabled' => false,
        ]);

        $this->actingAs($user);
        $response = $this->get(route('backend.security.permission.index'));

        $response->assertRedirect($this->loginRoute());
    }

    /** @test */
    public function enabled_frontend_users_may_not_view_the_permission_index()
    {
        $user = $this->make(Account::class, [
            'is_enabled' => true,
            'is_backend' => false,
        ]);

        $this->actingAs($user);
        $response = $this->get(route('backend.security.permission.index'));

        $response->assertRedirect(config('core.frontend_routes_prefix'));
    }

    /** @test */
    public function enabled_unverified_backend_users_may_not_view_the_permission_index()
    {
        $user = $this->make(Account::class, [
            'is_enabled' => true,
            'is_backend' => true,
            'email_verified_at' => null,
        ]);

        $this->actingAs($user);
        $response = $this->get(route('backend.security.permission.index'));

        $response->assertRedirect($this->verificationNoticeRoute());
    }

    /** @test */
    public function enabled_verified_backend_users_without_permission_may_not_view_the_permission_index()
    {
        $user = $this->make(Account::class, [
            'is_enabled' => true,
            'is_backend' => true,
        ]);

        $this->actingAs($user);
        $this->from(config('core.backend_routes_prefix'));
        $response = $this->get(route('backend.security.permission.index'));

        $response->assertRedirect(config('core.backend_routes_prefix'));
    }

    /** @test */
    public function enabled_verified_backend_users_with_permission_can_view_the_permission_index()
    {
        $user = $this->backendUser(['backend.security.permission.index']);

        $this->actingAs($user);
        $response = $this->get(route('backend.security.permission.index'));

        $response->assertSuccessful();
        $response->assertViewIs('core::backend.security.permission.index');
    }
}
