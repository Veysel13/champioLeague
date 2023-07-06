<?php

namespace App\Libraries;

use App\Models\MatchModel;
use App\Models\MatchScore;
use App\Models\Standing;

class Game
{

    private $oneTeamResult;
    private $twoTeamResult;
    private $week;
    private $matchId;

    public function __construct($matchId, $week)
    {
        $this->oneTeamResult = null;
        $this->twoTeamResult = null;
        $this->week = $week;
        $this->matchId = $matchId;
    }

    public function play()
    {
        $match = MatchModel::where('id', $this->matchId)->first();

        if ($match) {
            $matchScores = MatchScore::where('match_id',$match->id)->get();

            if ($matchScores && $matchScores->count() == 2) {

                $oneTeamScore = $matchScores[0];
                $twoTeamScore = $matchScores[1];

                if ($oneTeamScore && $twoTeamScore) {

                    $this->oneTeamResult = new TeamScore($oneTeamScore->team_id, $this->week);
                    $this->twoTeamResult = new TeamScore($twoTeamScore->team_id, $this->week);

                    $this->oneTeamResult->setPlayedMatch(1);
                    $this->twoTeamResult->setPlayedMatch(1);

                    $this->oneTeamResult->setGoalCount($oneTeamScore->score);
                    $this->twoTeamResult->setGoalCount($twoTeamScore->score);

                    $this->oneTeamResult->setGoalAgainst($twoTeamScore->score);
                    $this->twoTeamResult->setGoalAgainst($oneTeamScore->score);

                    if ($oneTeamScore->score > $twoTeamScore->score) {
                        $this->oneTeamResult->setPoint(3);

                        $this->oneTeamResult->setWin(1);
                        $this->twoTeamResult->setLose(1);
                    } else if ($twoTeamScore->score > $oneTeamScore->score) {
                        $this->twoTeamResult->setPoint(3);

                        $this->twoTeamResult->setWin(1);
                        $this->oneTeamResult->setLose(1);
                    } else {
                        $this->oneTeamResult->setDraw(1);
                        $this->twoTeamResult->setDraw(1);

                        $this->oneTeamResult->setPoint(1);
                        $this->twoTeamResult->setPoint(1);
                    }
                }
            }
        }
    }

    public function setStandingForWeek(){

        Standing::create([
            'team_id' => $this->oneTeamResult->getTeam()->id,
            'point' => $this->oneTeamResult->getPoint(),
            'played_match' => $this->oneTeamResult->getPlayedMatch(),
            'win' => $this->oneTeamResult->getWin(),
            'draw' => $this->oneTeamResult->getDraw(),
            'lose' => $this->oneTeamResult->getLose(),
            'goal_count' => $this->oneTeamResult->getGoalCount(),
            'goal_against' => $this->oneTeamResult->getGoalAgainst(),
            'week' => $this->week,
        ]);

        Standing::create([
            'team_id' => $this->twoTeamResult->getTeam()->id,
            'point' => $this->twoTeamResult->getPoint(),
            'played_match' => $this->twoTeamResult->getPlayedMatch(),
            'win' => $this->twoTeamResult->getWin(),
            'draw' => $this->twoTeamResult->getDraw(),
            'lose' => $this->twoTeamResult->getLose(),
            'goal_count' => $this->twoTeamResult->getGoalCount(),
            'goal_against' => $this->twoTeamResult->getGoalAgainst(),
            'week' => $this->week,
        ]);

        return true;
    }
}
