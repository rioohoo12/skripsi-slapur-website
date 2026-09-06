<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Student;
use App\Models\Room;
use App\Models\RoomAssignment;
use Spatie\Permission\Models\Role;

class RoomSelectionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::create(['name' => 'Murid']);
    }

    private function createStudent($gender)
    {
        $user = User::factory()->create();
        $user->assignRole('Murid');
        
        $student = Student::create([
            'user_id' => $user->id,
            'nisn' => rand(100000, 999999),
            'gender' => $gender,
            'date_of_birth' => '2005-01-01',
        ]);
        
        return $user;
    }

    public function test_student_can_only_see_rooms_of_same_gender()
    {
        $maleStudent = $this->createStudent('L');
        
        Room::create(['name' => 'Kamar Putra 1', 'capacity' => 4, 'gender_type' => 'L']);
        Room::create(['name' => 'Kamar Putri 1', 'capacity' => 4, 'gender_type' => 'P']);

        $response = $this->actingAs($maleStudent)->getJson('/api/rooms/available');
        
        $response->assertStatus(200);
        $this->assertCount(1, $response->json());
        $this->assertEquals('L', $response->json()[0]['gender_type']);
    }

    public function test_student_cannot_assign_room_of_opposite_gender()
    {
        $femaleStudent = $this->createStudent('P');
        
        $maleRoom = Room::create(['name' => 'Kamar Putra 2', 'capacity' => 4, 'gender_type' => 'L']);

        $response = $this->actingAs($femaleStudent)->postJson('/api/rooms/assign', [
            'room_id' => $maleRoom->id,
            'academic_year' => '2026'
        ]);
        
        $response->assertStatus(403);
    }
    
    public function test_cannot_assign_full_room()
    {
        $maleStudent = $this->createStudent('L');
        $maleStudent2 = $this->createStudent('L');
        
        $room = Room::create(['name' => 'Kamar Putra Kecil', 'capacity' => 1, 'gender_type' => 'L']);
        
        // Fill the room
        $this->actingAs($maleStudent)->postJson('/api/rooms/assign', [
            'room_id' => $room->id,
            'academic_year' => '2026'
        ])->assertStatus(200);

        // Try to assign second student to full room
        $response = $this->actingAs($maleStudent2)->postJson('/api/rooms/assign', [
            'room_id' => $room->id,
            'academic_year' => '2026'
        ]);
        
        $response->assertStatus(422)
                 ->assertJson(['message' => 'Kamar sudah penuh.']);
    }
}
