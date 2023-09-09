<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Requests\Users\CreateUserValidator;
use App\Requests\Users\LoginUserValidator;
use App\Services\UserService;
use Auth;
use Illuminate\Http\Request;

class AuthController extends BaseController
{
    //Service Dependency Injection in the constructor
    protected UserService $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    public function register(CreateUserValidator $createUserValidator)
    {
        if (!empty($createUserValidator->getErrors())){
            return response()->json($createUserValidator->getErrors(),406);
        }

        $user = $this->userService->createUser($createUserValidator->request()->all());
        $message['user'] = $user;
        $message['token'] = $user->createToken('InnoscriptaNews')->plainTextToken;
        return $this->sendReponse($message);
    }

    public function login(LoginUserValidator $loginUserValidator)
    {
        if (!empty($loginUserValidator->getErrors())){
            return response()->json($loginUserValidator->getErrors(),406);
        }

        $request = $loginUserValidator->request();

        if(Auth::attempt(['email'=>$request->email, 'password'=>$request->password]))
        {
            $user = Auth::user();
            $success['token'] = $user->createToken('InnoscriptaNews')->plainTextToken;
            $success['fullName'] = $user->fullName;

            return $this->sendReponse($success);
        }
        else
        {
            return $this->sendReponse('Unauthorized','fail', 401 );
        }
    }
}
