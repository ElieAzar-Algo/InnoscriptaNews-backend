<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Auth;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    protected UserService $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function storeUserPreferences(Request $request)
    {
        return $this->userService->attachPreferences($request);
    }

    public function getUser()
    {
        //get the authenticated user by token instead of id
        $user = Auth::user();
        $user->load('preferences'); // Eager load the preferences relationship
        if($user) return $user;
    }
}
