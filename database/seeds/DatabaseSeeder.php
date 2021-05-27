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
        $Permissions = array(
            ['name' => 'ticket input'],
            ['name' => 'view own tickets'],
            ['name' => 'view own department tickets'],
            ['name' => 'view assigned tickets'],
            ['name' => 'view all tickets'],
            ['name' => 'view archived tickets'],
            ['name' => 'edit ticket status'],
            ['name' => 'edit ticket types'],
            ['name' => 'edit ticket'],
            ['name' => 'assign employee'],
            ['name' => 'unassign employee'],
            ['name' => 'edit employee']
        );

        for ($i=0; $i < count($Permissions); $i++) { 
            Permission::create($Permissions[$i]);
            $role = Role::create($Permissions[$i]);

            $role->givePermissionTo($Permissions[$i]["name"]);
        }  

        // $this->call(UserSeeder::class);
    }
}
