<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;


class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function sendResponse($result, $message, $statusCode = ResponseAlias::HTTP_OK): \Illuminate\Http\JsonResponse
    {
        $response = [
            'result'    => $result,
            'message' => $message,
        ];

        return response()->json($response, $statusCode);
    }
}
