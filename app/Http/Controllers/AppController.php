<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRegistrationRequest;
use App\Repository\UserRepositoryInterface;

class AppController extends Controller
{
    private $userRepo;
    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->middleware('AuthJwt', ['except' => ['registration', 'login']]);
        $this->userRepo = $userRepo;
    }

    public function registration(UserRegistrationRequest $request)
    {
        $response = $this->userRepo->save($request->all());
        return response()->json(['message'=>$response->message], $response->status);
    }

    public function login(LoginRequest $request)
    {
        $response = $this->userRepo->login($request->all());        
        return response()->json($response, $response->status);
    }

    public function logout()
    {
        $response = $this->userRepo->logout();        
        return response()->json(['message'=>$response->message], $response->status);
    }
}
