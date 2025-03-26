<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AchievementResource;
use Illuminate\Http\Request;
use App\Models\Achievement;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AchievementController extends Controller
{
    public function index()
    {
        $achievements = Achievement::all();
        // ->orderBy('id', 'asc')
        // ->paginate(50);

        return AchievementResource::collection($achievements);
    }
    public function show($id)
    {
        $achievement = Achievement::find($id);
        return new AchievementResource($achievement);
    }

    public function update(Request $request, $id)
    {
        $achievement = Achievement::findOrFail($id);

        // Validar los datos del formulario
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'achievement_type' => 'required|string|in:games,chips,wins',
            'amount' => 'required|integer|min:0',
            'reward_amount' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validación de la imagen
        ]);

        // Actualizar los datos del logro
        $achievement->update($validatedData);
        $achievement->clearMediaCollection('Achievements'); // Elimina la imagen anterior
        $achievement->addMediaFromRequest('image')->toMediaCollection('Achievements');

        return response()->json([
            'message' => 'Achievement updated successfully',
            'achievement' => new AchievementResource($achievement),
        ]);
    }

    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'achievement_type' => 'required|string|in:games,chips,wins',
            'amount' => 'required|integer|min:0',
            'reward_amount' => 'required|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validación de la imagen
        ]);

        // Crear un nuevo logro
        $achievement = Achievement::create($validatedData);
        $achievement->addMediaFromRequest('image')->toMediaCollection('Achievements');

        return response()->json([
            'message' => 'Achievement created successfully',
            'achievement' => new AchievementResource($achievement),
        ]);
    }
}
