<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccountRegistered;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Handle user registration for ALL roles.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'gender' => 'required|in:L,P',
            'role' => 'required|string',
        ]);

        // Create the user with gender
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'gender' => $request->gender,
        ]);

        // Assign requested role
        $role = Role::where('name', $request->role)->first();
        if ($role) {
            $user->assignRole($role);
        }

        // Specific handling for 'Murid'
        if ($request->role === 'Murid') {
            // Generate dummy NISN since we don't ask for it in generic form
            Student::create([
                'user_id' => $user->id,
                'nisn' => rand(10000000, 99999999), 
                'gender' => $request->gender,
                'date_of_birth' => '2005-01-01', // Dummy
            ]);
        }

        // Specific handling for 'Guru'
        if ($request->role === 'Guru') {
            \App\Models\Teacher::create([
                'user_id' => $user->id,
                'nip' => 'NIP' . rand(100000, 999999), // Dummy NIP
            ]);
        }

        // Send confirmation email
        try {
            Mail::to($user->email)->send(new AccountRegistered($user));
        } catch (\Exception $e) {
            \Log::error("Failed to send registration email to {$user->email}: " . $e->getMessage());
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $request->role
            ]
        ], 201);
    }

    /**
     * Handle user login.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Kredensial yang Anda masukkan salah.'],
            ]);
        }

        if (!$user->is_active) {
            return response()->json([
                'message' => 'Akun Anda telah dinonaktifkan. Silakan hubungi Administrator.'
            ], 403);
        }

        if (!$user->hasRole($request->role)) {
            return response()->json([
                'message' => 'Anda tidak memiliki akses ke halaman role ini.'
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $request->role,
                'roles' => $user->getRoleNames()
            ]
        ]);
    }

    /**
     * Handle user logout.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    /**
     * Get authenticated user details.
     */
    public function me(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'roles' => $user->getRoleNames()
        ]);
    }

    /**
     * Request OTP for Forgot Password
     */
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);
        
        $otp = rand(100000, 999999);
        
        // Save OTP
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => $otp, // Store OTP in token column
                'created_at' => Carbon::now()
            ]
        );

        // Dummy send email for OTP
        \Log::info("OTP for {$request->email} is: {$otp}");
        // In real app: Mail::to($request->email)->send(new OtpSent($otp));

        return response()->json([
            'message' => 'Kode OTP telah dikirim ke email Anda.'
        ]);
    }

    /**
     * Reset Password using OTP
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $reset = DB::table('password_resets')->where('email', $request->email)->first();

        if (!$reset || $reset->token !== $request->otp) {
            return response()->json(['message' => 'Kode OTP tidak valid.'], 400);
        }

        // Check expiration (15 minutes)
        if (Carbon::parse($reset->created_at)->addMinutes(15)->isPast()) {
            return response()->json(['message' => 'Kode OTP telah kedaluwarsa.'], 400);
        }

        // Reset password
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete OTP
        DB::table('password_resets')->where('email', $request->email)->delete();

        return response()->json([
            'message' => 'Password berhasil diubah. Silakan login kembali.'
        ]);
    }
}
