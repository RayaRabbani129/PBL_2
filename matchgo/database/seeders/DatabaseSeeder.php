<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create roles
        Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'admin_field', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'auditor', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'player', 'guard_name' => 'web']);

        // User::factory(10)->create();

        // Create Admin User
        $admin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@matchgo.local',
            'password' => bcrypt('password'),
            'phone' => '082123456789',
        ]);
        $admin->assignRole('super_admin');

        // Create Field Admin User
        $fieldAdmin = User::factory()->create([
            'name' => 'Field Admin',
            'email' => 'fieldadmin@matchgo.local',
            'password' => bcrypt('password'),
            'phone' => '082123456790',
        ]);
        $fieldAdmin->assignRole('admin_field');

        // Create Auditor User
        $auditor = User::factory()->create([
            'name' => 'Auditor',
            'email' => 'auditor@matchgo.local',
            'password' => bcrypt('password'),
            'phone' => '082123456791',
        ]);
        $auditor->assignRole('auditor');

        // Create Test Player User
        $player = User::factory()->create([
            'name' => 'Test Player',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'phone' => '082123456792',
        ]);
        $player->assignRole('player');
    }
}
