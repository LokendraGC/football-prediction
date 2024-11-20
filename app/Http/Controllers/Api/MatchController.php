<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FootballMatch;
use Illuminate\Http\Request;

class MatchController extends Controller
{

    public function storeMatch(Request $request)
    {
        $match_data = $request->validate([
            'match_date' => 'required|date|after_or_equal:today',
            'home_team_id' => 'required|exists:teams,id',
            'away_team_id' => 'required|exists:teams,id|different:home_team_id',
            'home_score' => 'required|integer|min:0',
            'away_score' => 'required|integer|min:0', // Ensures the away score is a non-negative integer
        ]);

        $match = FootballMatch::create($match_data);

        if (!$match) {
            return response()->json(['message' => 'unable to create Match'], 400);
        }
        return response()->json(['message' => 'Football match created successfully'], 201);
    }

    public function fetchMatch()
    {
        $match_data = FootballMatch::all();
        return $match_data;
    }

    public function editMatch($id)
    {
        $match = FootballMatch::find($id);

        if (!$match) {
            return response()->json(['message' => 'Match Not found']);
        }

        return response()->json(['match' => $match], 200);
    }

    public function updateMatch(Request $request, $id)
    {

        $match = FootballMatch::find($id);

        if (!$match) {
            return response()->json(['message' => 'Team not found'], 404);
        }

        // return $request->all();

        $request->validate([
            'match_date' => 'required|date|after_or_equal:today',
            'home_team_id' => 'required|exists:teams,id',
            'away_team_id' => 'required|exists:teams,id|different:home_team_id',
            'home_score' => 'required|integer|min:0',
            'away_score' => 'required|integer|min:0', // Ensures the away score is a non-negative integer
        ]);

        $match->match_date = $request->match_date;
        $match->home_team_id = $request->home_team_id;
        $match->away_team_id = $request->away_team_id;
        $match->home_score = $request->home_score;
        $match->away_score = $request->away_score;


        $match->save();

        return response()->json(['message' => 'Match updated successfully', 'match' => $match], 200);
    }

    public function deleteMatch(FootballMatch $match)
    {
        // route model binding
        // $match = FootballMatch::find($match);

        if (!$match) {
            return response()->json(['message' => 'Match not found'], 404);
        }

        $match->delete();

        return response()->json(['message' => 'Match deleted successfully'], 200);
    }
}
