<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $movies = Movie::with('genres')->get();

        return response()->json(
            $movies,                
            200,          
            [],                    
            JSON_UNESCAPED_UNICODE 
        );
    }

    /**
     * @param int|string
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $movie = Movie::with(['genres', 'reviews' => function ($q) {
            $q->where('status', 'approved')->with('likes');
        }])->findOrFail($id);

        return response()->json(
            $movie,             
            200,                    
            [],                     
            JSON_UNESCAPED_UNICODE  
        );
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'title' => 'required|string',
            'director' => 'required|string',
            'year' => 'required|integer',
            'country' => 'required|string',
            'description' => 'required|string',
            'posterUrl' => 'nullable|string',
            'genres' => 'array'
        ]);

        $fields['createdBy'] = $request->user()->id;

        $movie = Movie::create($fields);

        if (isset($fields['genres'])) {
            $movie->genres()->sync($fields['genres']);
        }

        return response()->json($movie->load('genres'), 201);
    }

    public function update(Request $request, $id)
    {
        $movie = Movie::findOrFail($id);
        $user = $request->user();

        if ($movie->createdBy !== $user->id && !in_array($user->role, ['moderator', 'admin'])) {
            return response()->json(['message' => 'Доступ запрещен'], 403);
        }

        $fields = $request->validate([
            'title' => 'sometimes|string',
            'director' => 'sometimes|string',
            'year' => 'sometimes|integer',
            'country' => 'sometimes|string',
            'description' => 'sometimes|string',
            'posterUrl' => 'nullable|string'
        ]);

        $movie->update($fields);

        return response()->json($movie);
    }

    public function destroy(Request $request, $id)
    {
        $movie = Movie::findOrFail($id);
        $user = $request->user();

        if (!in_array($user->role, ['moderator', 'admin'])) {
            return response()->json(['message' => 'Доступ запрещен'], 403);
        }

        $movie->delete();

        return response()->json(['message' => 'Фильм удален']);
    }
}