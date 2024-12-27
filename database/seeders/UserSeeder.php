<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = DB::table('roles')->where('name', 'admin')->first();

        DB::table('users')->insert([
            ['id' => Str::uuid(), 'name' => 'admin', 'email' => 'admin1@gmail.com', 'password' => Hash::make('password'), 'role_id' => $role->id],
        ]);
    }
}