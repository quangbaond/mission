<?php

namespace App\Services;

use App\Repositories\IntroduceRepository;
use App\Services\BaseService;

class IntroduceService extends BaseService
{

    protected IntroduceRepository $introduceRepository;

    public function __construct(IntroduceRepository $introduceRepository)
    {
        parent::__construct($introduceRepository);
        $this->introduceRepository = $introduceRepository;
    }
}
