<?php

namespace App\Modules\Core\Tests\Models;

use App\Modules\Core\Models\Account;
use App\Modules\Core\Models\Permission;
use App\Modules\Core\Models\Role;
use App\Modules\Core\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_many_permissions()
    {
        $permissionOne = $this->create(Permission::class);
        $permissionTwo = $this->create(Permission::class);

        $role = $this->create(Role::class);

        $role->permissions()->save($role, ['permission_id' => $permissionOne->id]);
        $role->permissions()->save($role, ['permission_id' => $permissionTwo->id]);

        $this->assertTrue($role->permissions->contains($permissionOne));
        $this->assertTrue($role->permissions->contains($permissionTwo));
    }

    /** @test */
    public function it_has_many_accounts()
    {
        $accountOne = $this->create(Account::class);
        $accountTwo = $this->create(Account::class);

        $role = $this->create(Role::class);

        $role->accounts()->save($role, ['account_id' => $accountOne->id]);
        $role->accounts()->save($role, ['account_id' => $accountTwo->id]);

        $this->assertTrue($role->accounts->contains($accountOne));
        $this->assertTrue($role->accounts->contains($accountTwo));
    }
}
