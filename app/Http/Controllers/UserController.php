<?php

namespace App\Http\Controllers;

use App\Services\IntroduceService;
use App\Services\UserService;
use App\Services\WithDrawService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UserController extends Controller
{

    protected UserService $userService;

    protected WithDrawService $withDrawService;

    protected IntroduceService $introduceService;

    public function __construct(UserService $userService, WithDrawService $withDrawService, IntroduceService $introduceService)
    {
        $this->userService = $userService;
        $this->withDrawService = $withDrawService;
        $this->introduceService = $introduceService;
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

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */

    public function updateProfile(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:' . FORM_INPUT_MAX_LENGTH],
            'phone' => ['nullable', 'string', 'max:' . FORM_INPUT_MAX_LENGTH, 'unique:users,phone,' . Auth::id()],
            'address' =>  ['nullable', 'string', 'max:' . FORM_INPUT_MAX_LENGTH],
            'email' => ['required', 'string', 'email', 'max:' . FORM_INPUT_MAX_LENGTH],
            'password' => ['nullable', 'string', 'confirmed'],
            'password_confirmation' => ['nullable', 'string'],
            'password_old' => ['nullable', 'string'],
        ],[
            'name.required' => 'Vui lòng nhập họ tên',
            'name.max' => 'Họ tên không được vượt quá ' . FORM_INPUT_MAX_LENGTH . ' ký tự',
            'phone.max' => 'Số điện thoại không được vượt quá ' . FORM_INPUT_MAX_LENGTH . ' ký tự',
            'phone.unique' => 'Số điện thoại đã tồn tại',
            'address.max' => 'Địa chỉ không được vượt quá ' . FORM_INPUT_MAX_LENGTH . ' ký tự',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
            'email.max' => 'Email không được vượt quá ' . FORM_INPUT_MAX_LENGTH . ' ký tự',
            'password.confirmed' => 'Mật khẩu không khớp',

        ]);

        if ($request->password_old && !Hash::check($request->password_old, Auth::user()->password)) {
            return $this->sendResponse([],'Mật khẩu cũ không đúng', ResponseAlias::HTTP_BAD_REQUEST);
        }
        if($request->phone && !Auth::user()->phone_verified_at){
            $request->merge(['phone_verified_at' => now()]);
        }

        $this->userService->update($request->all(), Auth::id());
        $user = $this->userService->find(id: Auth::id());
        return $this->sendResponse($user, 'Update user successfully', ResponseAlias::HTTP_OK);
    }

    /**
     * @throws \Exception
     */
    public function getIntroduce(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = $this->userService->find(id: Auth::id());
        $introduceTotal = $user->introduce()->count();

        $introduceVerifyTotal = $user->introduce()->whereHas('introducedVerified')->count();
        return $this->sendResponse(['introduceTotal' => $introduceTotal, 'introduceVerifyTotal' => $introduceVerifyTotal], 'Get introduce successfully', ResponseAlias::HTTP_OK);
    }
}
