<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Developer;

class DeveloperController extends Controller
{
    public function readAll()
    {
        $devs = Developer::all();
        return response()->json($devs);
    }

    public function readOne($id)
    {
        $dev = Developer::find($id);
        if (!$dev) return response()->json(['message' => 'Not found'], 404);
        return response()->json($dev);
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'nullable|string|max:255',
        ]);

        $dev = Developer::create($data);
        return response()->json($dev, 201);
    }

    public function update(Request $request, $id)
    {
        $dev = Developer::find($id);
        if (!$dev) return response()->json(['message' => 'Not found'], 404);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'nullable|string|max:255',
        ]);

        $dev->update($data);
        return response()->json($dev);
    }

    public function delete($id)
    {
        $dev = Developer::find($id);
        if (!$dev) return response()->json(['message' => 'Not found'], 404);
        $dev->delete();
        return response()->json(['message' => 'Soft deleted']);
    }
}
