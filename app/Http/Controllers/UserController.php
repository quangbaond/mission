<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UserController extends Controller
{

    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @return JsonResponse
     */
    public function profile(): \Illuminate\Http\JsonResponse
    {

        $user = $this->userService->find(id: Auth::id(), withCount: ['userMission']);
        return $this->sendResponse($user, 'Get user successfully', ResponseAlias::HTTP_OK);
    }

}
