<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class DeveloperSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Salman Alfarisi',
            'email' => 'superdeveloper@gmail.com',
            'phone' => '082233445566',
            'password' => Hash::make('salman123'),
            'role' => 'developer',
            'photo' => null,
        ]);
    }
}
