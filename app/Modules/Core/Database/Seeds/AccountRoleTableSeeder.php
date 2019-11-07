<?php

namespace App\Modules\Core\Database\Seeds;

use App\Modules\Core\Models\Account;
use App\Modules\Core\Models\Role;
use Illuminate\Database\Seeder;

class AccountRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // super.admin
        $role = Role::findOrFail(1);
        $account = Account::findOrFail(1);
        $account->roles()->attach($role->id);
    }
}
