<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Get all users with their roles
     */
    public function index()
    {
        $users = User::with('roles')->latest()->get();
        return response()->json($users);
    }

    /**
     * Change user role
     */
    public function changeRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name'
        ]);

        $user = User::findOrFail($id);
        
        // Ensure not removing the last admin (basic safeguard)
        if ($user->hasRole('Admin') && User::role('Admin')->count() <= 1 && $request->role !== 'Admin') {
            return response()->json(['message' => 'Tidak dapat mengubah role satu-satunya Admin.'], 400);
        }

        DB::beginTransaction();
        try {
            $user->syncRoles([$request->role]);
            DB::commit();

            return response()->json([
                'message' => 'Role berhasil diubah menjadi ' . $request->role,
                'user' => $user->load('roles')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal mengubah role.'], 500);
        }
    }

    /**
     * Toggle user active status (Ban / Unban)
     */
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        if ($user->hasRole('Admin') && User::role('Admin')->count() <= 1 && $user->is_active) {
            return response()->json(['message' => 'Tidak dapat menonaktifkan satu-satunya Admin.'], 400);
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return response()->json([
            'message' => 'Akun berhasil ' . $status,
            'user' => $user->load('roles')
        ]);
    }
}
