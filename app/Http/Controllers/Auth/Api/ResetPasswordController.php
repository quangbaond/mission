<?php

namespace App\Http\Controllers\Auth\Api;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Auth\PasswordResetRequest;
use App\Services\PasswordService;
use App\Http\Requests\Auth\ConfirmTokenRequest;

class ResetPasswordController extends ApiController
{
    protected $passwordService;

    public function __construct(PasswordService $passwordService)
    {
        $this->passwordService = $passwordService;
    }
    /**
     * Create token password reset and send email to user
     *
     * @param  ResetPasswordRequest $request
     * @return JsonResponse
     */
    public function sendMail(PasswordResetRequest $request)
    {
        $result = $this->passwordService->sendEmail($request);
        if(!$result) return $this->responseError(trans('message.email_not_found') , 422);
        return $this->responseSuccess($result , 200);
    }

    /**
     * Reset new password
     */
    public function reset(ConfirmTokenRequest $request)
    {
        $result = $this->passwordService->reset($request);
        if(!$result) return $this->responseError(trans('message.link_expect') , 422);
        return $this->responseSuccess($result , 200);
    }
}
