<?php

namespace App\Http\Controllers\Auth\Api;

use App\Http\Controllers\Controller;
use App\Services\IntroduceService;
use App\Services\Traits\AuthenticatesUsers;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class RegisterController extends Controller
{
    protected UserService $userService;

    protected IntroduceService $introduceService;

    /**
     * @param UserService $userService
     * @param IntroduceService $introduceService
     */
    public function __construct(UserService $userService, IntroduceService $introduceService)
    {
        $this->userService = $userService;
        $this->introduceService = $introduceService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'email|required|unique:users',
            'password' => 'required|string',
        ]);

        $requester = $request->all();
        $requester['password'] = Hash::make($request->password);

        $user = $this->userService->create($requester);

        if ($request->has('ref')) {
            $userByRef = $this->userService->findBy(['code' => $request->ref])->first();
            $introduce = $this->introduceService->findBy(['user_id' => $userByRef->id, 'introduced_id' => $user->id])->first();

            if (!$introduce) {
                $this->introduceService->create([
                    'user_id' => $userByRef->id,
                    'introduced_id' => $user->id,
                ]);
            }
        }

        return $this->sendResponse($user, 'User register successfully', ResponseAlias::HTTP_CREATED);
    }
}
