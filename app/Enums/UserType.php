<?php

namespace App\Enums;

enum UserType:int
{
    case ADMIN = 1;
    case ADVERTISER = 2;
    case CLIENT = 3;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Ambitio',//__('admin.admin'),
            self::ADVERTISER => __('admin.advertiser'),
            self::CLIENT => __('admin.client'),
        };
    }

    public static function keyValue(): array
    {
        return [
            self::ADMIN->value => 'Ambitio',//__('admin.admin'),
            self::ADVERTISER->value => __('admin.advertiser'),
            self::CLIENT->value => __('admin.client'),
        ];
    }
}
