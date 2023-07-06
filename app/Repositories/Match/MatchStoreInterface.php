<?php

namespace App\Repositories\Match;

use App\Models\MatchScore;

interface MatchStoreInterface
{

    public function findById(int $id): ?MatchScore;

    public function create(array $data): MatchScore;
}
