<?php

namespace App\Modules\Core\Tests\Backend;

use App\Modules\Core\Models\Account;
use App\Modules\Core\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_may_not_access_the_index_page()
    {
        $response = $this->get(route('backend.index'));

        $response->assertRedirect($this->loginRoute());
    }

    /** @test */
    public function disabled_users_may_not_access_the_index_page()
    {
        $user = $this->make(Account::class, [
            'is_enabled' => false,
        ]);

        $this->actingAs($user);
        $response = $this->get(route('backend.index'));

        $response->assertRedirect($this->loginRoute());
    }

    /** @test */
    public function enabled_frontend_users_may_not_access_the_index_page()
    {
        $user = $this->make(Account::class, [
            'is_enabled' => true,
            'is_backend' => false,
        ]);

        $this->actingAs($user);
        $response = $this->get(route('backend.index'));

        $response->assertRedirect(config('core.frontend_routes_prefix'));
    }

    /** @test */
    public function enabled_backend_users_can_access_the_index_page()
    {
        $user = $this->make(Account::class, [
            'is_enabled' => true,
            'is_backend' => true,
        ]);

        $this->actingAs($user);
        $response = $this->get(route('backend.index'));

        $response->assertSuccessful();
        $response->assertViewIs('core::backend.index');
    }
}
