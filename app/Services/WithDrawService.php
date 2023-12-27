<?php

namespace App\Services;

use App\Repositories\Eloquent\WithDrawRepository;
use App\Services\BaseService;

class WithDrawService extends BaseService
{
    protected WithDrawRepository $withDrawRepository;

    public function __construct(WithDrawRepository $withDrawRepository)
    {
        parent::__construct($withDrawRepository);
        $this->withDrawRepository = $withDrawRepository;
    }
}
