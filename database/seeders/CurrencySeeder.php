<?php

namespace Database\Seeders;

use App\Enums\UserType;
use App\Models\City;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Language;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = [
            [
                'name' => json_encode(['en' => 'United Arab Emirates Dirham', 'ar' => 'درهم إماراتي']),
                'code' => 'AED',
                'symbol' => 'د.إ',
                'exchange_rate' => 3.6725,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => json_encode(['en' => 'Egyptian Pound', 'ar' => 'جنيه مصري']),
                'code' => 'EGP',
                'symbol' => '£',
                'exchange_rate' => 47.68,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => json_encode(['en' => 'Saudi Riyal', 'ar' => 'ريال سعودي']),
                'code' => 'SAR',
                'symbol' => 'ر.س',
                'exchange_rate' => 3.75,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        if (!Currency::query()->count() > 0) {
            Currency::insert($currencies);
        }
    }
}
