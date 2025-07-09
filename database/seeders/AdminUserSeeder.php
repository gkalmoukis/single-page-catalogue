<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Giorgos Kalmoukis',
            'email' => 'giorgoskalmoukis@theloom.gr',
            'password' => Hash::make(config('default.admin_password')),
        ]);
    }
}
