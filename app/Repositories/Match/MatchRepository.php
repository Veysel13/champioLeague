<?php

namespace App\Repositories\Match;

use App\Models\MatchModel;

class MatchRepository implements MatchInterface
{

    public function findById(int $id): ?MatchModel
    {
        return MatchModel::find($id);
    }

    public function create(array $data): MatchModel
    {
        return MatchModel::create($data);
    }
}
