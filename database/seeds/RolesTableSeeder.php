<?php

namespace Database\Seeds;

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = config('roles.models.role')::create([
            'name' => 'Admin',
            'slug' => 'admin',
            'description' => '',
            'level' => 5,
        ]);
        
        $itRole = config('roles.models.role')::create([
            'name' => 'IT',
            'slug' => 'it',
            'description' => 'It Role',
            'level' => 4,
        ]);

        $vendorRole = config('roles.models.role')::create([
            'name' => 'Vendor',
            'slug' => 'vendor',
            'description' => 'Vendor Role',
            'level' => 1,
        ]);
   }
}
