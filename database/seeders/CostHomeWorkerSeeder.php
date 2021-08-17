<?php

namespace Database\Seeders;

use App\Models\CostHomeWorker;
use Illuminate\Database\Seeder;

class CostHomeWorkerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['truss_elements' => 3, 'cost' => 40 ],
            ['truss_elements' => 4, 'cost' => 45 ],
            ['truss_elements' => 5, 'cost' => 50 ],
            ['truss_elements' => 6, 'cost' => 55 ],
            ['truss_elements' => 7, 'cost' => 65 ],
            ['truss_elements' => 8, 'cost' => 65 ],
            ['truss_elements' => 9, 'cost' => 70 ],
            ['truss_elements' => 10, 'cost' => 75 ],
            ['truss_elements' => 11, 'cost' => 75 ],
            ['truss_elements' => 12, 'cost' => 85 ],
            ['truss_elements' => 13, 'cost' => 85 ],
            ['truss_elements' => 14, 'cost' => 90 ],
            ['truss_elements' => 15, 'cost' => 90 ],
            ['truss_elements' => 16, 'cost' => 95 ],
            ['truss_elements' => 17, 'cost' => 100 ],
            ['truss_elements' => 18, 'cost' => 105 ],
            ['truss_elements' => 19, 'cost' => 110 ],
            ['truss_elements' => 20, 'cost' => 115 ],
            ['truss_elements' => 21, 'cost' => 120 ],
            ['truss_elements' => 22, 'cost' => 120 ],
            ['truss_elements' => 23, 'cost' => 125 ],
            ['truss_elements' => 24, 'cost' => 125 ],
            ['truss_elements' => 25, 'cost' => 130 ],
            ['truss_elements' => 26, 'cost' => 130 ],
        ];
        CostHomeWorker::insert($data);
    }
}
