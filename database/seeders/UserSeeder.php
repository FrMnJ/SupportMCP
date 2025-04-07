<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::query()->create([
            'name' => 'Admin User',
            'email' => 'admin@mail.com',
            'number' => '123456789',
            'extension' => '123',
            'department' => 'Dev',
            'password' => bcrypt('password'),
        ]);

        $adminUser->assignRole('admin');

        $developerUser = User::query()->create([
            'name' => 'Developer User',
            'email' => 'developer@mail.com',
            'number' => '987654321',
            'extension' => '456',
            'department' => 'Dev',
            'password' => bcrypt('password'),
        ]);
        $developerUser->assignRole('developer');

        $supportUser = User::query()->create([
            'name' => 'Support User',
            'email' => 'support@mail.com',
            'number' => '456789123',
            'extension' => '789',
            'department' => 'Support',
            'password' => bcrypt('password'),
        ]);

        $supportUser->assignRole('support');

        $clientUser = User::query()->create([
            'name' => 'Client User',
            'email' => 'client@mail.com',
            'number' => '321654987',
            'extension' => '321',
            'department' => 'Client',
            'password' => bcrypt('password'),
        ]);

        $clientUser->assignRole('client');

        $agentUser = User::query()->create([
            'name' => 'Agent User',
            'email' => 'agent@mail.com',
            'number' => '390298340',
            'extension' => '321',
            'department' => 'Dev',
            'password' => bcrypt('password'),
        ]);

        $agentUser->assignRole('agent');
        $token = $agentUser->createToken('Agent Token')->plainTextToken;
        echo "Agent Token: $token\n";

    }
}
