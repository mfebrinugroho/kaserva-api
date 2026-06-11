<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('stores')->insert([
            [
                'name' => 'Dapur Nusantara',
                'slug' => 'dapur-nusantara',
                'description' => 'Restoran khas Indonesia dengan berbagai menu tradisional dan modern.',
                'image' => null,
                'banner' => null,
                'address' => 'Jl. Ahmad Yani No. 12, Pontianak',
                'phone' => '081234567890',
                'latitude' => -0.02633000,
                'longitude' => 109.34250000,
                'is_open' => true,
                'closed_reason' => '',
                'accept_order' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'Kopi Senja',
                'slug' => 'kopi-senja',
                'description' => 'Coffee shop modern dengan suasana nyaman untuk bekerja dan nongkrong.',
                'image' => null,
                'banner' => null,
                'address' => 'Jl. Gajah Mada No. 45, Pontianak',
                'phone' => '082345678901',
                'latitude' => -0.03021000,
                'longitude' => 109.34110000,
                'is_open' => true,
                'closed_reason' => '',
                'accept_order' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'Ayam Geprek Mantap',
                'slug' => 'ayam-geprek-mantap',
                'description' => 'Tempat makan ayam geprek pedas dengan berbagai level sambal.',
                'image' => null,
                'banner' => null,
                'address' => 'Jl. Tanjungpura No. 88, Pontianak',
                'phone' => '083456789012',
                'latitude' => -0.02875000,
                'longitude' => 109.34490000,
                'is_open' => false,
                'closed_reason' => 'Tutup Sementara',
                'accept_order' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('store_operating_hours')->insert([
            // Dapur Nusantara (store_id = 1)
            [
                'store_id' => 1,
                'day_of_week' => 0,
                'open_time' => '08:00:00',
                'close_time' => '21:00:00',
                'is_open' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => 1,
                'day_of_week' => 1,
                'open_time' => '08:00:00',
                'close_time' => '21:00:00',
                'is_open' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => 1,
                'day_of_week' => 2,
                'open_time' => '08:00:00',
                'close_time' => '21:00:00',
                'is_open' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => 1,
                'day_of_week' => 3,
                'open_time' => '08:00:00',
                'close_time' => '21:00:00',
                'is_open' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => 1,
                'day_of_week' => 4,
                'open_time' => '08:00:00',
                'close_time' => '21:00:00',
                'is_open' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => 1,
                'day_of_week' => 5,
                'open_time' => '08:00:00',
                'close_time' => '22:00:00',
                'is_open' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => 1,
                'day_of_week' => 6,
                'open_time' => '08:00:00',
                'close_time' => '22:00:00',
                'is_open' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Kopi Senja (store_id = 2)
            [
                'store_id' => 2,
                'day_of_week' => 0,
                'open_time' => '10:00:00',
                'close_time' => '23:00:00',
                'is_open' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => 2,
                'day_of_week' => 1,
                'open_time' => '08:00:00',
                'close_time' => '22:00:00',
                'is_open' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => 2,
                'day_of_week' => 2,
                'open_time' => '08:00:00',
                'close_time' => '22:00:00',
                'is_open' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => 2,
                'day_of_week' => 3,
                'open_time' => '08:00:00',
                'close_time' => '22:00:00',
                'is_open' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => 2,
                'day_of_week' => 4,
                'open_time' => '08:00:00',
                'close_time' => '22:00:00',
                'is_open' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => 2,
                'day_of_week' => 5,
                'open_time' => '08:00:00',
                'close_time' => '23:30:00',
                'is_open' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => 2,
                'day_of_week' => 6,
                'open_time' => '08:00:00',
                'close_time' => '23:30:00',
                'is_open' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Ayam Geprek Mantap (store_id = 3)
            [
                'store_id' => 3,
                'day_of_week' => 0,
                'open_time' => null,
                'close_time' => null,
                'is_open' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => 3,
                'day_of_week' => 1,
                'open_time' => '09:00:00',
                'close_time' => '20:00:00',
                'is_open' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => 3,
                'day_of_week' => 2,
                'open_time' => '09:00:00',
                'close_time' => '20:00:00',
                'is_open' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => 3,
                'day_of_week' => 3,
                'open_time' => '09:00:00',
                'close_time' => '20:00:00',
                'is_open' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => 3,
                'day_of_week' => 4,
                'open_time' => '09:00:00',
                'close_time' => '20:00:00',
                'is_open' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => 3,
                'day_of_week' => 5,
                'open_time' => '09:00:00',
                'close_time' => '21:00:00',
                'is_open' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'store_id' => 3,
                'day_of_week' => 6,
                'open_time' => '09:00:00',
                'close_time' => '21:00:00',
                'is_open' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
