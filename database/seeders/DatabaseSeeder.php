<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Role
        Role::firstOrCreate(['name' => 'Admin']);
        Role::firstOrCreate(['name' => 'Dosen']);
        Role::firstOrCreate(['name' => 'Mahasiswa']);
        Role::firstOrCreate(['name' => 'Kajur']);

        // Admin pertama
        $admin = User::firstOrCreate(
    ['email' => 'admin@iot.com'],
    [
        'name' => 'Administrator',
        'nim_nip' => '9001',
        'password' => Hash::make('12345678'),
        'status' => true,
    ]
);
        $admin->assignRole('Admin');
        $this->call(DeviceSeeder::class);
    }
}