<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            return response()->json(Auth::user(), 201);
        }else{
            return response()->json(['message' => 'Unauthorised'], 401);
        }
    }
}
