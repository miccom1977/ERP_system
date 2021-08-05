<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'view'],
            ['name' => 'edit'],
            ['name' => 'manage']
        ];
        Permission::insert($data);
    }
}
