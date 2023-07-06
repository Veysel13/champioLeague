<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Standing extends Model
{

    protected $fillable = [
        'team_id',
        'point',
        'played_match',
        'win',
        'draw',
        'lose',
        'goal_count',
        'goal_against',
        'week',
    ];

    public static function calculateScore($week, $teamId)
    {
        $standingWeeks = Standing::where('week', $week)->where('team_id', $teamId)->first();

        return (($standingWeeks->win + ($standingWeeks->draw * 0.5)) / $standingWeeks->played_match) * 50;
    }
}
