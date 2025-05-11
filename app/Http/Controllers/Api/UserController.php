<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
     // Placeholder for profile methods
    public function profile(Request $request)
    {
        return new UserResource($request->user());
        return response()->json($request->user());
    }
}