<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Services\MissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;


class MissionController extends Controller
{
    protected MissionService $missionService;

    protected UserService $userService;

    public function __construct(MissionService $missionService, UserService $userService)
    {
        $this->missionService = $missionService;
        $this->userService = $userService;
    }

    /**
     * @throws \Exception
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $missions = [];
        $missions['countMissionAvailable'] = $this->missionService->withCount([]);
        $missions['countMissionCompleted'] = $this->userService->userMission([], true);

        $requestMission = $request->all();
        $requestMission['all'] = true;
        $requestMission['with'] = ['userMission'];
        $missions['missionList'] = $this->missionService->pagination(requester: $requestMission);
        return $this->sendResponse($missions, 'Get missions successfully');
    }

    public function doTask(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'mission_id' => 'required|integer',
        ]);
        $mission = $this->missionService->find($request->mission_id);

        $userMission = $this->userService->doTask(['mission_id' => $mission->id, 'user_id' => Auth::id(), 'reward' => $mission->reward]);
        return $this->sendResponse($userMission, 'You have completed this mission', ResponseAlias::HTTP_CREATED);
    }

}
