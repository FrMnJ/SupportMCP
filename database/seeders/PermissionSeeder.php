<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name'=>'create tickets'])
            ->syncRoles(['admin', 'developer', 'support', 'agent']);

        Permission::create(['name'=>'read tickets'])
            ->syncRoles(['admin', 'developer', 'support', 'agent']);

        Permission::create(['name'=>'update tickets'])
            ->syncRoles(['admin', 'developer', 'support']);

        Permission::create(['name'=>'delete tickets'])
            ->syncRoles(['admin']);

        Permission::create(['name'=>'create users'])
            ->syncRoles(['admin']);
        Permission::create(['name'=>'read users'])
            ->syncRoles(['admin', 'developer', 'support']);
        
        Permission::create(['name'=>'update users'])
            ->syncRoles(['admin', 'developer', 'support']);
        Permission::create(['name'=>'delete users'])
            ->syncRoles(['admin']);
    }
}
