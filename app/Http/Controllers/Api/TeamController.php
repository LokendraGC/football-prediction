<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{
    public function storeTeam(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'stadium' => 'required|string|max:255',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate logo
        ]);

        // Handle the logo file upload
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $logoPath = $file->store('logos', 'public'); // Store in 'storage/app/public/logos'
            $fileName = basename($logoPath);

            Team::create([
                'name' => $request->name,
                'stadium' => $request->stadium,
                'logo' => $fileName, // Save the path to the database
            ]);

            return response()->json(['message' => 'Team created successfully'], 201);
        }
        return response()->json(['error' => 'Logo file is required'], 400);
    }

    public function fetchTeam()
    {
        $team_data = Team::all();
        return $team_data;
    }

    public function editTeam($id)
    {
        $team = Team::find($id);

        if (!$team) {
            return response()->json(['message' => 'Team Not found']);
        }

        return response()->json(['team' => $team], 200);
    }

    public function updateTeam(Request $request, $id)
    {

        $team = Team::find($id);

        if (!$team) {
            return response()->json(['message' => 'Team not found'], 404);
        }

        // return $request->all();

        $request->validate([
            'name' => 'required|string|max:255',
            'stadium' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Optional logo validation
        ]);



        $team->name = $request->name;
        $team->stadium = $request->stadium;

        if ($request->hasFile('logo')) {
            if ($team->logo) {
                Storage::disk('public')->delete('logos/' . $team->logo);
            }

            $logo_path = $request->file('logo')->store('logos', 'public');
            $fileName = basename($logo_path);
            $team->logo = $fileName;
        }

        $team->save();

        return response()->json(['message' => 'Team updated successfully', 'team' => $team], 200);
    }

    public function deleteTeam($id)
    {
        $team = Team::find($id);

        if (!$team) {
            return response()->json(['message' => 'Team not found'], 404);
        }

        $team->delete();

            return response()->json(['message' => 'Team deleted successfully'], 200);

    }
}
