<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Role;

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
        $admin = User::updateOrCreate(
            ['email' => 'admin@matchgo.local'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
                'phone' => '082123456789',
            ]
        );
        $admin->syncRoles(['super_admin']);

        // Create Field Admin User
        $fieldAdmin = User::updateOrCreate(
            ['email' => 'fieldadmin@matchgo.local'],
            [
                'name' => 'Field Admin',
                'password' => bcrypt('password'),
                'phone' => '082123456790',
            ]
        );
        $fieldAdmin->syncRoles(['admin_field']);

        // Create Auditor User
        $auditor = User::updateOrCreate(
            ['email' => 'auditor@matchgo.local'],
            [
                'name' => 'Auditor',
                'password' => bcrypt('password'),
                'phone' => '082123456791',
            ]
        );
        $auditor->syncRoles(['auditor']);

        // Create Test Player User
        $player = User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test Player',
                'password' => bcrypt('password'),
                'phone' => '082123456792',
            ]
        );
        $player->syncRoles(['player']);

        // Seed referees
        $this->call(RefereeSeeder::class);
    }
}
