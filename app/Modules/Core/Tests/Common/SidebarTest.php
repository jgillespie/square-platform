<?php

namespace App\Modules\Core\Tests\Common;

use App\Modules\Core\Models\Account;
use App\Modules\Core\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SidebarTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_may_not_save_the_sidebar_toggled_state()
    {
        $response = $this->post(route('common.sidebar.toggle'));

        $response->assertRedirect($this->loginRoute());
        $response->assertSessionMissing('sidebarToggled');
    }

    /** @test */
    public function disabled_users_may_not_save_the_sidebar_toggled_state()
    {
        $user = $this->make(Account::class, [
            'is_enabled' => false,
        ]);

        $this->actingAs($user);
        $response = $this->post(route('common.sidebar.toggle'));

        $response->assertRedirect($this->loginRoute());
        $response->assertSessionMissing('sidebarToggled');
    }

    /** @test */
    public function enabled_users_can_save_the_sidebar_toggled_state()
    {
        $user = $this->make(Account::class, [
            'is_enabled' => true,
        ]);

        $this->actingAs($user);
        $response = $this->post(route('common.sidebar.toggle'));

        $response->assertSuccessful();
        $response->assertSessionHas('sidebarToggled', true);
    }

    /** @test */
    public function enabled_users_can_change_the_sidebar_toggled_state()
    {
        session(['sidebarToggled' => true]);

        $user = $this->make(Account::class, [
            'is_enabled' => true,
        ]);

        $this->actingAs($user);
        $response = $this->post(route('common.sidebar.toggle'));

        $response->assertSuccessful();
        $response->assertSessionHas('sidebarToggled', false);

        $response = $this->post(route('common.sidebar.toggle'));

        $response->assertSuccessful();
        $response->assertSessionHas('sidebarToggled', true);
    }
}
