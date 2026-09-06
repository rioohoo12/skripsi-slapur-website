<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Staff;
use App\Models\Room;
use App\Models\RoomAssignment;
use App\Models\AcademicSchedule;
use App\Models\Grade;
use App\Models\Attendance;
use App\Models\Payment;
use App\Models\DiningService;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 1. Roles
        $adminRole = Role::create(['name' => 'Admin']);
        $muridRole = Role::create(['name' => 'Murid']);
        $guruRole = Role::create(['name' => 'Guru']);
        $staffRole = Role::create(['name' => 'Staff Administrasi']);

        // 2. Users
        $adminUser = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@slapur.com',
            'password' => Hash::make('password'),
        ]);
        $adminUser->assignRole($adminRole);

        $muridUser = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@slapur.com',
            'password' => Hash::make('password'),
        ]);
        $muridUser->assignRole($muridRole);

        $guruUser = User::create([
            'name' => 'Pak Joko',
            'email' => 'joko@slapur.com',
            'password' => Hash::make('password'),
        ]);
        $guruUser->assignRole($guruRole);

        $staffUser = User::create([
            'name' => 'Ibu Siti',
            'email' => 'siti@slapur.com',
            'password' => Hash::make('password'),
        ]);
        $staffUser->assignRole($staffRole);

        // 3. Profiles
        $student = Student::create([
            'user_id' => $muridUser->id,
            'nisn' => '1234567890',
            'gender' => 'L',
            'date_of_birth' => '2005-05-15',
            'address' => 'Jl. Merdeka No.1',
            'parent_phone' => '081234567890'
        ]);

        $teacher = Teacher::create([
            'user_id' => $guruUser->id,
            'nip' => '198012345678',
            'specialization' => 'Matematika'
        ]);

        Staff::create([
            'user_id' => $staffUser->id,
            'position' => 'Kepala Asrama',
            'department' => 'Dormitory'
        ]);

        // 4. Rooms & Assignments
        $room = Room::create([
            'name' => 'Asrama Putra A1',
            'capacity' => 4,
            'gender_type' => 'L'
        ]);

        RoomAssignment::create([
            'room_id' => $room->id,
            'student_id' => $student->id,
            'academic_year' => '2026/2027',
            'status' => 'active'
        ]);

        // 5. Academic
        AcademicSchedule::create([
            'teacher_id' => $teacher->id,
            'subject_name' => 'Matematika Dasar',
            'day_of_week' => 'Senin',
            'start_time' => '08:00:00',
            'end_time' => '10:00:00',
            'location' => 'Kelas 10A'
        ]);

        Grade::create([
            'student_id' => $student->id,
            'teacher_id' => $teacher->id,
            'subject_name' => 'Matematika Dasar',
            'semester' => 'Ganjil',
            'score' => 85.50
        ]);

        Attendance::create([
            'student_id' => $student->id,
            'date' => '2026-09-04',
            'status' => 'present'
        ]);

        // 6. Payments
        Payment::create([
            'student_id' => $student->id,
            'amount' => 1500000.00,
            'payment_date' => '2026-09-01',
            'type' => 'tuition',
            'status' => 'paid'
        ]);
        
        // 7. Dining Service History
        DiningService::create([
            'student_id' => $student->id,
            'date' => '2026-09-04',
            'meal_type' => 'breakfast',
            'menu_served' => 'Nasi Goreng',
            'status' => 'eaten'
        ]);
    }
}
