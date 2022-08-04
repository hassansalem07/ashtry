<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        
        $superAdmin = Role::create(['name' => 'super admin','guard_name' => 'admin']);
        $admin = Role::create(['name' => 'admin','guard_name' => 'admin']);
        $editor = Role::create(['name' => 'editor','guard_name' => 'admin']);


        Permission::create(['name' => 'list','guard_name' => 'admin']);
        Permission::create(['name' => 'create','guard_name' => 'admin']);
        Permission::create(['name' => 'update','guard_name' => 'admin']);
        Permission::create(['name' => 'delete','guard_name' => 'admin']);
        Permission::create(['name' => 'manage admins','guard_name' => 'admin']);



        $superAdmin->syncPermissions(['list','create','update','delete','manage admins']);
        $admin->syncPermissions(['list','create','update','delete']);
        $editor->syncPermissions(['list','create','update']);

    }
}