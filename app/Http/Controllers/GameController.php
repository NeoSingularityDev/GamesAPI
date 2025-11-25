<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;

class GameController extends Controller
{
    public function readAll()
    {
        $games = Game::with('developer')->get();
        return response()->json($games);
    }

    public function readOne($id)
    {
        $game = Game::with('developer')->find($id);
        if (!$game) return response()->json(['message' => 'Not found'], 404);
        return response()->json($game);
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'year' => 'nullable|integer',
            'developer_id' => 'required|exists:developers,id',
        ]);

        $game = Game::create($data);
        return response()->json($game->load('developer'), 201);
    }

    public function update(Request $request, $id)
    {
        $game = Game::find($id);
        if (!$game) return response()->json(['message' => 'Not found'], 404);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'year' => 'nullable|integer',
            'developer_id' => 'required|exists:developers,id',
        ]);

        $game->update($data);
        return response()->json($game->load('developer'));
    }

    public function delete($id)
    {
        $game = Game::find($id);
        if (!$game) return response()->json(['message' => 'Not found'], 404);
        $game->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
