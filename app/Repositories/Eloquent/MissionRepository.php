<?php

namespace App\Repositories\Eloquent;

use App\Models\Mission;
use App\Repositories\Eloquent\BaseRepository;

class MissionRepository extends BaseRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Mission::class;
    }

}
