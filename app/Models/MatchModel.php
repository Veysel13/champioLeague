<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchModel extends Model
{
    protected $table = "matches";
    protected $fillable = [
        'team_ids',
        'week',
        'match_date'
    ];
}
