<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $managerData = [
            'name'     => 'Manager',
            'phone'    => '1234567890',
            'email'    => 'manager@example.com',
            'password' => Hash::make('secret123'),
            'role'     => 'manager',
        ];

        User::updateOrCreate(
            ['phone' => '1234567890'],
            $managerData
        );
    }
}
