<?php

namespace App\Repositories\Team;

use App\Models\Team;

class TeamRepository implements TeamInterface
{

    public function findById(int $id): ?Team
    {
        return Team::find($id);
    }

    public function create(array $data): Team
    {
        return Team::create($data);
    }
}
