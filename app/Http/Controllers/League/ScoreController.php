<?php

namespace App\Http\Controllers\League;

use App\Http\Controllers\Controller;
use App\Libraries\Game;
use App\Models\MatchModel;
use App\Models\MatchScore;
use App\Models\Standing;
use App\Models\Team;
use App\Repositories\Match\MatchInterface;
use App\Repositories\Match\MatchStoreInterface;
use Illuminate\Http\Request;

class ScoreController extends Controller
{

    public $match;
    public $matchStore;

    public function __construct(MatchInterface $match,MatchStoreInterface $matchStore)
    {
        $this->match = $match;
        $this->matchStore = $matchStore;
    }

    public function index()
    {
        $blade = [];

        $standingWeeks = Standing::get();

        $teams = Team::whereIn('id', $standingWeeks->pluck('team_id'))->get();

        $standingWeeks->map(function ($standingWeek) use ($teams) {
            $team = $teams->where('id', $standingWeek->team_id)->first();
            $standingWeek->teamName = $team ? $team->name : '';

            $standingWeek->winPercentage = Standing::calculateScore($standingWeek->week, $standingWeek->team_id);
            $standingWeek->winPercentage = number_format($standingWeek->winPercentage, 2, '.', '');
        });

        $blade['matchScore'] = [];

        $matchScores = MatchScore::select('match_scores.*', 'matches.week')
            ->join('matches', 'matches.id', '=', 'match_scores.match_id')
            ->whereIn('week', $standingWeeks->pluck('week'))
            ->get();

        $matchScores->map(function ($matchScore) use ($teams) {
            $team = $teams->where('id', $matchScore->team_id)->first();
            $matchScore->teamName = $team ? $team->name : '';
        });

        $blade['standingWeeks'] = $standingWeeks;
        $blade['matchScores'] = $matchScores;

        return view('league.index', $blade);
    }

    public function xhrWeekScore(Request $request)
    {
        $blade = [];

        $standingWeeks = Standing::where('week',$request->input('week'))
            ->orderBy('point','desc')
            ->paginate(4);

        $teams = Team::whereIn('id', $standingWeeks->pluck('team_id'))->get();

        $standingWeeks->map(function ($standingWeek) use ($teams) {
            $team = $teams->where('id', $standingWeek->team_id)->first();
            $standingWeek->teamName = $team ? $team->name : '';

            $standingWeek->winPercentage = Standing::calculateScore($standingWeek->week, $standingWeek->team_id);
            $standingWeek->winPercentage = number_format($standingWeek->winPercentage, 2, '.', '');
        });

        $blade['draw'] = $request->input('draw');
        $blade['recordsTotal'] = $standingWeeks->total();
        $blade['recordsFiltered'] = $standingWeeks->total();
        $blade['data'] = $standingWeeks->toArray()['data'];
        return response()->json($blade);
    }

    public function play(Request $request)
    {
        $endMatch = MatchModel::orderBy('week', 'desc')->first();
        $week = $endMatch ? $endMatch->week : 0;
        $completeWeek = $request->input('completeWeek', false);

        if ($completeWeek) {
            $playWeek = 16 - $week;
        } else {
            $playWeek = 1;
        }

        if ($week>=16){
            return response()->json([
                'status'=>false,
                'errors'=>[['Champion League IS OVER']]
            ],500);
        }

        for ($w = 1; $w <= $playWeek; $w++) {
            $teams = Team::where('status', 1)->pluck('id')->toArray();

            $matches = [];

            shuffle($teams);
            for ($i = 0; $i < count($teams); $i += 2) {
                $match = [$teams[$i], $teams[$i + 1]
                ];
                $matches[] = $match;
            }

            foreach ($matches as $match) {

                $matchMoodel = $this->match->create([
                    'team_ids' => json_encode($match, 1),
                    'week' => $week + 1,
                    'match_date' => now()
                ]);

                $matchScores = MatchScore::matchScore($week, $match[0], $match[1]);

                foreach ($matchScores as $matchScore) {
                    $this->matchStore->create([
                        'match_id' => $matchMoodel->id,
                        'team_id' => $matchScore['team_id'],
                        'score' => $matchScore['score'],
                    ]);
                }

                $game = new Game($matchMoodel->id, $matchMoodel->week);
                $game->play();
                $game->setStandingForWeek();
            }

            $week += 1;
        }

        session()->flash('success','Week Match Played');
        return response()->json([
            'status'=>true,
            'redirectUrl'=>route('score.index')
        ]);
    }
}
