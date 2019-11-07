<?php

namespace App\Modules\Core\Tests\Models;

use App\Modules\Core\Models\Account;
use App\Modules\Core\Models\Permission;
use App\Modules\Core\Models\Role;
use App\Modules\Core\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_many_roles()
    {
        $roleOne = $this->create(Role::class);
        $roleTwo = $this->create(Role::class);

        $account = $this->create(Account::class);

        $account->roles()->save($account, ['role_id' => $roleOne->id]);
        $account->roles()->save($account, ['role_id' => $roleTwo->id]);

        $this->assertTrue($account->roles->contains($roleOne));
        $this->assertTrue($account->roles->contains($roleTwo));
    }

    /** @test */
    public function it_can_check_if_it_has_a_specific_role_by_name()
    {
        $role = $this->create(Role::class);

        $account = $this->create(Account::class);
        $account->roles()->attach($role);

        $this->assertTrue($account->hasRole($role->name));
    }

    /** @test */
    public function it_can_check_if_it_has_a_specific_permission_by_name()
    {
        $permission = $this->create(Permission::class);

        $role = $this->create(Role::class);
        $role->permissions()->attach($permission);

        $account = $this->create(Account::class);
        $account->roles()->attach($role);

        $this->assertTrue($account->hasPermission($permission->name));
    }
}
