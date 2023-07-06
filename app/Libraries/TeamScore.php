<?php

namespace App\Libraries;

use App\Models\Standing;
use App\Models\Team;

class TeamScore
{
    private $team;
    private $point;
    private $playedMatch;
    private $win;
    private $draw;
    private $lose;
    private $goalCount;
    private $goalAgainst;

    public function __construct($teamId, $week)
    {
        $this->setData($teamId,$week);
    }

    public function setData($teamId, $week)
    {

        $standingTeamOne = Standing::where('week', $week - 1)->where('team_id', $teamId)->first();

        $this->team = Team::find($teamId);
        $this->point = $standingTeamOne->point ?? 0;
        $this->playedMatch = $standingTeamOne->played_match ?? 0;
        $this->win = $standingTeamOne->win ?? 0;
        $this->draw = $standingTeamOne->draw ?? 0;
        $this->lose = $standingTeamOne->lose ?? 0;
        $this->goalCount = $standingTeamOne->goal_count ?? 0;
        $this->goalAgainst = $standingTeamOne->goal_against ?? 0;
    }

    public function setPoint($point)
    {
        $this->point += $point;
    }

    public function setPlayedMatch($count = 1)
    {
        $this->playedMatch += $count;
    }

    public function setWin($count = 1)
    {
        $this->win += $count;
    }

    public function setDraw($count = 1)
    {
        $this->draw += $count;
    }

    public function setLose($count = 1)
    {
        $this->lose += $count;
    }

    public function setGoalCount($count)
    {
        $this->goalCount += $count;
    }

    public function setGoalAgainst($count)
    {
        $this->goalAgainst += $count;
    }

    public function getTeam()
    {
        return $this->team;
    }

    public function getPoint()
    {
        return $this->point;
    }

    public function getPlayedMatch()
    {
        return $this->playedMatch;
    }

    public function getWin()
    {
        return $this->win;
    }

    public function getDraw()
    {
        return $this->draw;
    }

    public function getLose()
    {
        return $this->lose;
    }

    public function getGoalCount()
    {
        return $this->goalCount;
    }

    public function getGoalAgainst()
    {
        return $this->goalAgainst;
    }

}
