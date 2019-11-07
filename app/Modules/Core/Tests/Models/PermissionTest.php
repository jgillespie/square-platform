<?php

namespace App\Modules\Core\Tests\Models;

use App\Modules\Core\Models\Permission;
use App\Modules\Core\Models\Role;
use App\Modules\Core\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_many_roles()
    {
        $roleOne = $this->create(Role::class);
        $roleTwo = $this->create(Role::class);

        $permission = $this->create(Permission::class);

        $permission->roles()->save($permission, ['role_id' => $roleOne->id]);
        $permission->roles()->save($permission, ['role_id' => $roleTwo->id]);

        $this->assertTrue($permission->roles->contains($roleOne));
        $this->assertTrue($permission->roles->contains($roleTwo));
    }
}
