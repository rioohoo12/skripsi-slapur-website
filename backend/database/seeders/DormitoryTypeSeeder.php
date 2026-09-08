<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DormitoryTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            ['name' => 'Hawk', 'category' => 'sederhana', 'gender' => 'L', 'capacity_per_room' => 4, 'room_count' => 20],
            ['name' => 'Cendrawasih', 'category' => 'standar', 'gender' => 'L', 'capacity_per_room' => 4, 'room_count' => 20],
            ['name' => 'Anex', 'category' => 'standar', 'gender' => 'L', 'capacity_per_room' => 4, 'room_count' => 20],
            ['name' => 'Jasmina', 'category' => 'sederhana', 'gender' => 'P', 'capacity_per_room' => 4, 'room_count' => 20],
            ['name' => 'Bougenville', 'category' => 'standar', 'gender' => 'P', 'capacity_per_room' => 4, 'room_count' => 20],
            ['name' => 'Crysant', 'category' => 'standar', 'gender' => 'P', 'capacity_per_room' => 4, 'room_count' => 20],
        ];

        foreach ($types as $type) {
            \App\Models\DormitoryType::updateOrCreate(['name' => $type['name']], $type);
        }
    }
}
