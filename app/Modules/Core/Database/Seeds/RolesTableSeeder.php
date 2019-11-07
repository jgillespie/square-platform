<?php

namespace App\Modules\Core\Database\Seeds;

use App\Modules\Core\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'super',
                '(Built-in) Super administrator.',
            ],
        ];

        foreach ($roles as $r) {
            $role = new Role;

            $role->name = $r[0];
            $role->description = $r[1];

            $role->save();
        }
    }
}
