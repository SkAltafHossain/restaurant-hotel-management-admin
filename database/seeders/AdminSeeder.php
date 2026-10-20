<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@restaurant.com'],
            [
                'name' => 'Admin User',
                'email' => 'admin@restaurant.com',
                'password' => Hash::make('admin123'),
                'phone' => '+1234567890',
                'role' => 'admin',
            ]
        );

        $this->command->info('Admin user created successfully.');
        $this->command->info('Email: admin@restaurant.com');
        $this->command->info('Password: admin123');
    }
}
