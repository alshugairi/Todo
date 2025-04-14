<?php

namespace Database\Seeders;

use App\Enums\UserType;
use App\Models\Language;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Language::firstOrCreate(
            ['name' => 'English','code' => 'en'],
            ['name' => 'English','code' => 'en' , 'created_at' => now(),'updated_at' => now()]
        );

        Language::firstOrCreate(
            ['name' => 'عربى','code' => 'ar'],
            ['name' => 'عربى','code' => 'ar', 'created_at' => now(),'updated_at' => now()]
        );
    }
}
