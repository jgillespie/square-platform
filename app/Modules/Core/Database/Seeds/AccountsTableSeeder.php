<?php

namespace App\Modules\Core\Database\Seeds;

use App\Modules\Core\Models\Account;
use Illuminate\Database\Seeder;

class AccountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // super.admin
        $account = new Account;

        $account->is_enabled = true;
        $account->is_backend = true;
        $account->email = 'super.admin@example.com';
        $account->email_verified_at = now();
        $account->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; // password

        $account->save();
    }
}
