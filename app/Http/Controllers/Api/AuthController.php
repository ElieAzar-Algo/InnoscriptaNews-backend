<?php

namespace App\Http\Controllers\Api;

use App\Requests\Users\CreateUserValidator;
use App\Requests\Users\LoginUserValidator;
use App\Services\UserService;
use Auth;
use Illuminate\Http\JsonResponse;

class AuthController extends BaseController
{
    //Service Dependency Injection in the constructor
    protected UserService $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    public function register(CreateUserValidator $createUserValidator) : JsonResponse
    {
        if (!empty($createUserValidator->getErrors())){
            return response()->json([
                "status" => 400,
                "errors" => $createUserValidator->getErrors()],400);
        }

        $user = $this->userService->createUser($createUserValidator->request()->all());
        // $user->preferences()->attach([1]);
        $user->load('preferences'); // Eager load the preferences relationship
        $message['user'] = $user;
        $message['token'] = $user->createToken('InnoscriptaNews')->plainTextToken;
        return $this->sendReponse($message);
    }

    public function login(LoginUserValidator $loginUserValidator) : JsonResponse
    {
        if (!empty($loginUserValidator->getErrors())){
            return response()->json([
                "status" => 400,
                "errors" => $loginUserValidator->getErrors()],400);
        }

        $request = $loginUserValidator->request();

        if(Auth::attempt(['email'=>$request->email, 'password'=>$request->password]))
        {
            $user = Auth::user();
            $success['token'] = $user->createToken('InnoscriptaNews')->plainTextToken;
            $user->load('preferences'); // Eager load the preferences relationship
            $success['user'] = $user;

            return $this->sendReponse($success);
        }
        else
        {
            return $this->sendReponse('Unauthorized','fail', 401 );
        }
    }
}
