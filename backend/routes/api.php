<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SampleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\StudentAcademicController;
use App\Http\Controllers\PaymentController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/password/forgot', [AuthController::class, 'forgotPassword']);
Route::post('/password/reset', [AuthController::class, 'resetPassword']);
Route::post('/payments/notification', [\App\Http\Controllers\PaymentController::class, 'notification'])->withoutMiddleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    
    // Chatbot Route
    Route::post('/chatbot/message', [\App\Http\Controllers\ChatbotController::class, 'sendMessage']);
    
    // Murid Only Routes
    Route::middleware('role:Murid')->group(function () {
        Route::get('/murid/dashboard', function() { return response()->json(['message' => 'Welcome Murid']); });
        
        // Rooms
        Route::get('/rooms/available', [RoomController::class, 'availableRooms']);
        Route::post('/rooms/assign', [RoomController::class, 'assignRoom']);
        Route::get('/rooms/my', [RoomController::class, 'myRoom']);
        
        // Academic
        Route::get('/academic/schedules', [StudentAcademicController::class, 'schedules']);
        Route::get('/academic/grades', [StudentAcademicController::class, 'grades']);
        
        // Payment
        Route::post('/payment/create', [PaymentController::class, 'createTransaction']);
    });

    Route::middleware('role:Guru')->group(function () {
        Route::get('/guru/dashboard', function() { return response()->json(['message' => 'Welcome Guru']); });
        
        Route::get('/teacher/attendance', [\App\Http\Controllers\TeacherAttendanceController::class, 'index']);
        Route::post('/teacher/attendance', [\App\Http\Controllers\TeacherAttendanceController::class, 'store']);
        
        Route::get('/teacher/grades', [\App\Http\Controllers\TeacherGradeController::class, 'index']);
        Route::post('/teacher/grades', [\App\Http\Controllers\TeacherGradeController::class, 'store']);
        
        Route::get('/teacher/materials', [\App\Http\Controllers\TeacherMaterialController::class, 'index']);
        Route::post('/teacher/materials', [\App\Http\Controllers\TeacherMaterialController::class, 'store']);
        Route::delete('/teacher/materials/{id}', [\App\Http\Controllers\TeacherMaterialController::class, 'destroy']);
    });
    // Modul Staff Administrasi
    Route::middleware('role:Staff Administrasi|Admin')->group(function () {
        Route::get('/staff/dashboard', [\App\Http\Controllers\StaffReportController::class, 'dashboard']);
        
        Route::get('/staff/students', [\App\Http\Controllers\StaffStudentController::class, 'index']);
        Route::post('/staff/students', [\App\Http\Controllers\StaffStudentController::class, 'store']);
        Route::put('/staff/students/{id}', [\App\Http\Controllers\StaffStudentController::class, 'update']);
        Route::delete('/staff/students/{id}', [\App\Http\Controllers\StaffStudentController::class, 'destroy']);
        
        Route::get('/staff/payments', [\App\Http\Controllers\StaffPaymentController::class, 'index']);
        Route::post('/staff/payments/{id}/verify', [\App\Http\Controllers\StaffPaymentController::class, 'verify']);
        
        Route::get('/staff/rooms', [\App\Http\Controllers\StaffRoomController::class, 'index']);
        Route::get('/staff/rooms/{id}', [\App\Http\Controllers\StaffRoomController::class, 'show']);
        Route::post('/staff/rooms/{roomId}/assign', [\App\Http\Controllers\StaffRoomController::class, 'assignStudent']);
        Route::delete('/staff/room-assignments/{assignmentId}', [\App\Http\Controllers\StaffRoomController::class, 'removeOccupant']);
        
        Route::get('/staff/dining', [\App\Http\Controllers\StaffDiningController::class, 'index']);
        Route::post('/staff/dining', [\App\Http\Controllers\StaffDiningController::class, 'store']);
    });

    // Modul Admin
    Route::middleware('role:Admin')->group(function () {
        Route::get('/admin/dashboard', function() { return response()->json(['message' => 'Welcome Admin']); });
        Route::get('/admin/users', [\App\Http\Controllers\AdminController::class, 'index']);
        Route::put('/admin/users/{id}/role', [\App\Http\Controllers\AdminController::class, 'changeRole']);
        Route::put('/admin/users/{id}/toggle-status', [\App\Http\Controllers\AdminController::class, 'toggleStatus']);
    });

    Route::apiResource('samples', SampleController::class);
});
