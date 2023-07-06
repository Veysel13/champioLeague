<?php

namespace App\Repositories\Match;

use App\Models\MatchModel;

interface MatchInterface
{
    public function findById(int $id): ?MatchModel;

    public function create(array $data): MatchModel;
}
