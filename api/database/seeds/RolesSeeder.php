<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $startup = Role::create(['name' => 'startup']);
        $investor = Role::create(['name' => 'investor']);
        $manager = Role::create(['name' => 'manager']);

        Role::create(['name' => 'superadmin']);
    }
}
