<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Services\WithDrawService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UserController extends Controller
{

    protected UserService $userService;

    protected WithDrawService $withDrawService;

    public function __construct(UserService $userService, WithDrawService $withDrawService)
    {
        $this->userService = $userService;
        $this->withDrawService = $withDrawService;
    }

    /**
     * @return JsonResponse
     */
    public function profile(): \Illuminate\Http\JsonResponse
    {
        $user = $this->userService->find(id: Auth::id(), withCount: ['userMission']);
        return $this->sendResponse($user, 'Get user successfully', ResponseAlias::HTTP_OK);
    }

    public function withDraw(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'amount' => 'required|integer',
            'bank_name' => ['nullable', 'string', 'max:' . FORM_INPUT_MAX_LENGTH],
            'bank_number' => ['nullable', 'string', 'max:' . FORM_INPUT_MAX_LENGTH],
            'bank_owner' => ['nullable', 'string', 'max:' . FORM_INPUT_MAX_LENGTH],
            'method' => ['required', 'string', 'max:' . FORM_INPUT_MAX_LENGTH],
            'phone' => ['nullable', 'string', 'max:' . FORM_INPUT_MAX_LENGTH],
            'card_name' => ['nullable', 'string', 'max:' . FORM_INPUT_MAX_LENGTH],
            'card_number' => ['nullable', 'string', 'max:' . FORM_INPUT_MAX_LENGTH],
        ]);

        $requester = $request->all();
        $requester['user_id'] = Auth::id();
        if (Auth::user()->balance < $requester['amount']) {
            return $this->sendResponse([],'Số dư không đủ', ResponseAlias::HTTP_BAD_REQUEST);
        }
        $this->withDrawService->create($requester);

        $user = $this->userService->withDraw($request->all(), Auth::user());
        return $this->sendResponse($user, 'You have withdraw successfully', ResponseAlias::HTTP_CREATED);
    }

    public function getWithDraw(Request $request): \Illuminate\Http\JsonResponse
    {
        $requester = $request->all();
        $requester['user_id'] = Auth::id();
        $requester['all'] = true;
        $requester['orderBy'] = 'created_at';
        $withDraw = $this->withDrawService->pagination(requester: $requester);
        return $this->sendResponse($withDraw, 'Get user successfully', ResponseAlias::HTTP_OK);
    }

}
