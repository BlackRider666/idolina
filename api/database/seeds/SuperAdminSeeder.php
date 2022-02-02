<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = \App\Models\User\User::create([
            'name'    =>  'Super Admin',
            'email'         =>  'superadmin@idolina.com',
            'password'      =>  \Illuminate\Support\Facades\Hash::make('111111'),
            'country_id'    =>  1,
            'city_id'       =>  1,
        ]);
        $user->assignRole('superadmin');
    }
}
