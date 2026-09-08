<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dormitoryTypes = \App\Models\DormitoryType::all();

        foreach ($dormitoryTypes as $type) {
            for ($i = 1; $i <= $type->room_count; $i++) {
                \App\Models\Room::updateOrCreate(
                    [
                        'dormitory_type_id' => $type->id,
                        'room_number' => $i
                    ],
                    [
                        'capacity' => $type->capacity_per_room,
                        'occupied_count' => 0
                    ]
                );
            }
        }
    }
}
