<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        User::factory()->count(50)->create();
        $user = User::find(1);
        $user->name = '宋佳';
        $user->email = 'songjia774@sohu.com';
        $user->is_admin=true;
        $user->save();
    }
}
