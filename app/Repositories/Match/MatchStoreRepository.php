<?php

namespace App\Repositories\Match;

use App\Models\MatchScore;

class MatchStoreRepository implements MatchStoreInterface
{
    public function findById(int $id): ?MatchScore
    {
        return MatchScore::find($id);
    }

    public function create(array $data): MatchScore
    {
        return MatchScore::create($data);
    }
}
