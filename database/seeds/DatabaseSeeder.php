<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // create permissions
        Permission::create(['name' => 'ticket input']);
        Permission::create(['name' => 'view own tickets']);
        Permission::create(['name' => 'view own department tickets']);
        Permission::create(['name' => 'view all tickets']);

        Permission::create(['name' => 'edit ticket status']);
        Permission::create(['name' => 'edit ticket types']);
        Permission::create(['name' => 'edit ticket']);
        
        Permission::create(['name' => 'assign employee']);
        Permission::create(['name' => 'unassign employee']);
        Permission::create(['name' => 'edit employee']);

        // $this->call(UserSeeder::class);
    }
}
