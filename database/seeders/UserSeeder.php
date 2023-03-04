<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //administradores
        User::factory(1, [
            'dni' => '00000000T',
            'name' => 'admin',
            'apellidos' => 'admin admin',
            'rol' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make("admin")
        ])->create();

        //trabajadores
        User::factory(20)->create();
    }
}
