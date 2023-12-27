<?php

namespace App\Services;

use App\Repositories\Eloquent\MissionRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Services\BaseService;

class MissionService extends BaseService
{
    protected MissionRepository $missionRepository;

    public function __construct(MissionRepository $missionRepository)
    {
        parent::__construct($missionRepository);
        $this->missionRepository = $missionRepository;
    }

}
