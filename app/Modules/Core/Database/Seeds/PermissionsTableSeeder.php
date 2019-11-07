<?php

namespace App\Modules\Core\Database\Seeds;

use App\Modules\Core\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            [
                'backend.security.permission.index',
                'Display a listing of the permission.',
            ],
            [
                'backend.security.permission.index.data',
                'Process permission index datatables ajax request.',
            ],
            [
                'backend.security.role.index',
                'Display a listing of the role.',
            ],
            [
                'backend.security.role.index.data',
                'Process role index datatables ajax request.',
            ],
            [
                'backend.security.role.create',
                'Show the form for creating a new role.',
            ],
            [
                'backend.security.role.store',
                'Store a newly created role in storage.',
            ],
            [
                'backend.security.role.edit',
                'Show the form for editing the specified role.',
            ],
            [
                'backend.security.role.update',
                'Update the specified role in storage.',
            ],
            [
                'backend.security.role.destroy',
                'Remove the specified role from storage.',
            ],
            [
                'backend.security.account.index',
                'Display a listing of the account.',
            ],
            [
                'backend.security.account.index.data',
                'Process account index datatables ajax request.',
            ],
            [
                'backend.security.account.create',
                'Show the form for creating a new account.',
            ],
            [
                'backend.security.account.store',
                'Store a newly created account in storage.',
            ],
            [
                'backend.security.account.edit',
                'Show the form for editing the specified account.',
            ],
            [
                'backend.security.account.update',
                'Update the specified account in storage.',
            ],
            [
                'backend.security.account.destroy',
                'Remove the specified account from storage.',
            ],
        ];

        foreach ($permissions as $p) {
            $permission = new Permission;

            $permission->name = $p[0];
            $permission->description = $p[1];

            $permission->save();
        }
    }
}
