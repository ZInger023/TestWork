<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=1;$i<=2;$i++) {
            DB::table('users')->insert([
                'name' => 'user' . $i,
                'email' => 'user' . $i . '@gmail.com',
                'password' => Hash::make('31567199'),
                'role' => 'user'
            ]);
        }

        DB::table('users')->insert([
            'name' => 'Manager' ,
            'email' => 'manager123@gmail.com',
            'password' => Hash::make('31567199'),
            'role' => 'manager'
        ]);
    }
}
