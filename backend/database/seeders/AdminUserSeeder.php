<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        DB::table('users')->insertOrIgnore([
            'name'       => 'Maneesha Admin',
            'email'      => 'admin@maneeshafashion.lk',
            'phone'      => '0771234567',
            'password'   => Hash::make('Admin@1234'),
            'role'       => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create customer users
        DB::table('users')->insertOrIgnore([
            [
                'name'       => 'Test Customer',
                'email'      => 'customer@maneeshafashion.lk',
                'phone'      => '0711111111',
                'password'   => Hash::make('Customer@1234'),
                'role'       => 'customer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'John Doe',
                'email'      => 'john@example.com',
                'phone'      => '0722222222',
                'password'   => Hash::make('Password@1234'),
                'role'       => 'customer',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        $this->command->info('✅ Users created:');
        $this->command->info('   Admin Email   : admin@maneeshafashion.lk | Password: Admin@1234');
        $this->command->info('   Customer Email: customer@maneeshafashion.lk | Password: Customer@1234');
        $this->command->warn('   ⚠️  Please change the password after first login!');
    }
}
