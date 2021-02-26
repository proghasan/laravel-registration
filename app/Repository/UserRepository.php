<?php
namespace App\Repository;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use stdClass;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserRepository implements UserRepositoryInterface
{

    public function login($request)
    {
        $res = new stdClass;
        try {
            $credentials = [
                'email' => $request['email'],
                'password' => $request['password'],
            ];

            if (! $token = JWTAuth::attempt($credentials)) {
                $res->message =  "Email or password incorrect";
                $res->status = 404;
                return $res;
            }
        } catch (JWTException $e) {
            $res->message =  "Could not create token";
            $res->status = 422;
            return $res;
        }

        $res->token = 'Bearer '.$token;
        $res->user =  Auth()->user();
        $res->status =200;
        return $res;
    }

    public function save(array $request)
    {
        $res = new stdClass;
        try{
            $user = new User();
            $user->name = $request['name'];
            $user->email = $request['email'];
            $user->password = Hash::make($request['password']);
            $user->save();

            $res->message = "User registration successfully";
            $res->status = 201;
        }catch(\Exception $e){
            $res->message = "User registration fail";
            $res->status = 422;
        }
        return $res;
    }

    public function logout()
    {
        $res = new stdClass;
        try{
            auth()->logout();
            $res->message = "Logout successfully";
            $res->status = 200;
        }catch(\Exception $e){
            $res->message = "Logout fail";
            $res->status = 200;
        }
        return $res;
    }
}