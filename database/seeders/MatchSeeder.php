<?php

namespace Database\Seeders;

use App\Libraries\Game;
use App\Models\MatchModel;
use App\Models\MatchScore;
use App\Models\Standing;
use App\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class MatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MatchModel::truncate();
        MatchScore::truncate();
        Standing::truncate();

        $json = File::get("database/data/match.json");

        $matches = json_decode($json);

        foreach ($matches as $match) {
            foreach ($match as $team) {
                $teams = Team::whereIn('name', array_values($team->team_names))->pluck('id')->toArray();

                $matchData = MatchModel::create([
                    "team_ids" => json_encode($teams, 1),
                    "match_date" => $team->match_date,
                    "week" => $team->week
                ]);

                foreach ($team->scores as $score) {
                    $team = Team::where('name', $score->team_name)->first();
                    if ($team) {
                        MatchScore::create([
                            'match_id' => $matchData->id,
                            'team_id' => $team->id,
                            'score' => $score->score,
                        ]);
                    }
                }
            }
        }

        $matches = MatchModel::get();

        foreach ($matches as $match) {

            $game=new Game($match->id,$match->week);
            $game->play();
            $game->setStandingForWeek();
        }
    }
}
