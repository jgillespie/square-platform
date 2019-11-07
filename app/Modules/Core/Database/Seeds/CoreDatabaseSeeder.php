<?php

namespace App\Modules\Core\Database\Seeds;

use Illuminate\Database\Seeder;

class CoreDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissionsTableSeeder::class);

        $this->call(RolesTableSeeder::class);

        $this->call(AccountsTableSeeder::class);

        $this->call(PermissionRoleTableSeeder::class);

        $this->call(AccountRoleTableSeeder::class);
    }
}
