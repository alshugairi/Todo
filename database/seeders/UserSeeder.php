<?php

namespace Database\Seeders;

use App\Enums\UserType;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminByEmail = User::where('email', 'admin@admin.com')->first();

        $adminByUsername = User::where('username', 'admin')->first();

        if (!$adminByEmail && !$adminByUsername) {
            User::create([
                'type' => UserType::ADMIN->value,
                'username' => 'admin',
                'first_name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt('12345678')
            ]);
        }
    }
}
