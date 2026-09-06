<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LearningMaterial;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TeacherMaterialController extends Controller
{
    /**
     * Get list of uploaded materials
     */
    public function index(Request $request)
    {
        $teacherId = Auth::user()->teacher->id ?? 1;

        $materials = LearningMaterial::where('teacher_id', $teacherId)
            ->latest()
            ->get();

        $result = $materials->map(function ($m) {
            return [
                'id' => $m->id,
                'title' => $m->title,
                'file_url' => asset('storage/' . $m->file_path),
                'created_at' => $m->created_at->format('Y-m-d H:i')
            ];
        });

        return response()->json($result);
    }

    /**
     * Upload a new material
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:10240' // max 10MB
        ]);

        $teacherId = Auth::user()->teacher->id ?? 1;

        // Handle File Upload
        $path = $request->file('file')->store('materials', 'public');

        $material = LearningMaterial::create([
            'teacher_id' => $teacherId,
            'title' => $request->title,
            'file_path' => $path
        ]);

        return response()->json([
            'message' => 'Materi berhasil diunggah.',
            'material' => [
                'id' => $material->id,
                'title' => $material->title,
                'file_url' => asset('storage/' . $material->file_path),
                'created_at' => $material->created_at->format('Y-m-d H:i')
            ]
        ]);
    }
    
    /**
     * Delete a material
     */
    public function destroy($id)
    {
        $teacherId = Auth::user()->teacher->id ?? 1;
        $material = LearningMaterial::where('teacher_id', $teacherId)->findOrFail($id);
        
        Storage::disk('public')->delete($material->file_path);
        $material->delete();
        
        return response()->json(['message' => 'Materi berhasil dihapus.']);
    }
}
