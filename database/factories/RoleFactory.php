<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Role>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $role = fake()->randomElement(UserRole::cases());

        return [
            'slug' => $role->value,
            'name' => match ($role) {
                UserRole::SuperAdmin => 'Super Admin',
                UserRole::Admin => 'Admin',
                UserRole::Owner => 'Owner',
                UserRole::Customer => 'Customer',
                UserRole::Cashier => 'Cashier',
                UserRole::Kitchen => 'Kitchen',
            },
        ];
    }
}
