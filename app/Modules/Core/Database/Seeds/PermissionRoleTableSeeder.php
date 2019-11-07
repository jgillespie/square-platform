<?php

namespace App\Modules\Core\Database\Seeds;

use App\Modules\Core\Models\Permission;
use App\Modules\Core\Models\Role;
use Illuminate\Database\Seeder;

class PermissionRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // super
        $permissions = Permission::pluck('id')->toArray();
        $role = Role::findOrFail(1);
        $role->permissions()->sync($permissions);
    }
}
