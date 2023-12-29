<?php

namespace App\Repositories;

use App\Repositories\Eloquent\BaseRepository;

class IntroduceRepository extends Eloquent\BaseRepository
{

    /**
     * @inheritDoc
     */
    public function model(): string
    {
        return \App\Models\Introduce::class;
    }
}
