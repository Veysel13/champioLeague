<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchScore extends Model
{
    protected $fillable = [
        'match_id',
        'team_id',
        'score',
    ];

    public static function matchScore($week, $teamOneId, $teamTwoId)
    {
        $standingWeeks = Standing::where('week', $week)->whereIn('team_id', [$teamOneId, $teamTwoId])->get();

        $standingWeeks->map(function ($standingWeek) {

            $standingWeek->winPercentage = Standing::calculateScore($standingWeek->week, $standingWeek->team_id);
            $standingWeek->winPercentage = number_format($standingWeek->winPercentage, 2, '.', '');
        });

        $teamOneStanding = $standingWeeks->where('team_id', $teamOneId)->first();
        $teamTwoStanding = $standingWeeks->where('team_id', $teamTwoId)->first();

        if ($teamOneStanding && $teamTwoStanding) {
            if ($teamOneStanding->winPercentage > $teamTwoStanding->winPercentage) {
                $guessScore = intval($teamOneStanding->goal_count / $week);
                $scoreOne = rand(1, $guessScore);
                $scoreTwo = rand(0, $scoreOne);
                $result = [
                    ['team_id' => $teamOneStanding->team_id, 'score' => $scoreOne],
                    ['team_id' => $teamTwoStanding->team_id, 'score' => $scoreTwo],
                ];
            } else {
                $guessScore = intval($teamTwoStanding->goal_count / $week);
                $scoreOne = rand(1, $guessScore);
                $scoreTwo = rand(0, $scoreOne);

                $result = [
                    ['team_id' => $teamOneStanding->team_id, 'score' => $scoreTwo],
                    ['team_id' => $teamTwoStanding->team_id, 'score' => $scoreOne],
                ];
            }
        } else {
            $result = [
                ['team_id' => $teamOneStanding->team_id, 'score' => rand(0, 4)],
                ['team_id' => $teamTwoStanding->team_id, 'score' => rand(0, 4)],
            ];
        }

        return $result;
    }
}
