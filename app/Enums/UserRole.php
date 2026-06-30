<?php

namespace App\Enums;

enum UserRole: string
{
    case SuperAdmin = 'super-admin';
    case Admin = 'admin';
    case Owner = 'owner';
    case Customer = 'customer';
    case Cashier = 'cashier';
    case Kitchen = 'kitchen';

    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Super Admin',
            self::Admin => 'Admin',
            self::Owner => 'Owner',
            self::Customer => 'Pelanggan',
            self::Cashier => 'Kasir',
            self::Kitchen => 'Dapur',
        };
    }
}
