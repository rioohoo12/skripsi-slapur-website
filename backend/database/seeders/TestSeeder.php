<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Models\Student;
use App\Models\Room;

class TestSeeder extends Seeder
{
    public function run()
    {
        Role::firstOrCreate(['name' => 'Murid']);
        Role::firstOrCreate(['name' => 'Guru']);
        Role::firstOrCreate(['name' => 'Staff Administrasi']);
        Role::firstOrCreate(['name' => 'Admin']);
        
        $u = User::firstOrCreate(['email' => 'budi@slapur.com'], ['name' => 'Budi Tabuti', 'password' => bcrypt('password')]);
        $u->assignRole('Murid');
        
        Student::firstOrCreate(['user_id' => $u->id], ['nisn' => '12345678', 'gender' => 'L', 'date_of_birth' => '2005-01-01']);
        
        Room::firstOrCreate(['name' => 'Asrama Putra 1', 'capacity' => 10, 'gender_type' => 'L']);
        Room::firstOrCreate(['name' => 'Asrama Putri 1', 'capacity' => 10, 'gender_type' => 'P']);
        
        echo "Test data seeded.\n";
    }
}
