<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SampleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['message' => 'List of resources']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return response()->json(['message' => 'Resource created', 'data' => $request->all()], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return response()->json(['message' => "Details of resource {$id}"]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        return response()->json(['message' => "Resource {$id} updated", 'data' => $request->all()]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        return response()->json(['message' => "Resource {$id} deleted"]);
    }
}
