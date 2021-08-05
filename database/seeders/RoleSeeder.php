<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'employee'],
            ['name' => 'warehouseman'],
            ['name' => 'admin']
        ];
        Role::insert($data);
    }
}
