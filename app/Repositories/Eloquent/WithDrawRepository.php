<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\BaseRepository;

class WithDrawRepository extends BaseRepository
{

    /**
     * @inheritDoc
     */
    public function model(): string
    {
        return \App\Models\WithDraw::class;
    }
}
