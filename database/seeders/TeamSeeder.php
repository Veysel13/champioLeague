<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;


class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Team::truncate();

        $json = File::get("database/data/team.json");

        $teams = json_decode($json);

        foreach ($teams as $team) {

            $team = Team::create([
                "name" => $team->name,
                "country" => $team->country,
                "status" => $team->status,
            ]);
        }
    }
}
