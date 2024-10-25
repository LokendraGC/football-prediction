<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FootballMatch extends Model
{
    use HasFactory;
    protected $fillable = ['match_date', 'home_team_id', 'away_team_id', 'home_score', 'away_score'];
}
