<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->name = "Administrator";
        $user->email = "admin@admin.com";
        $user->password = bcrypt('pass');
        $user->role = 'admin';
        $user->email_verified_at = Carbon::now()->format('Y-m-d H:i:s');
        $user->save();
    }
}
