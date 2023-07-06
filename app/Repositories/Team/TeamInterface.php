<?php

namespace App\Repositories\Team;

use App\Models\Team;

interface TeamInterface
{
    public function findById(int $id): ?Team;

    public function create(array $data): Team;
}
