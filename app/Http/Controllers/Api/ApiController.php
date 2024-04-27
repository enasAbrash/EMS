<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiController extends Controller
{
    use ApiResponseTrait;
    //Register API (POST,from data)
    function register (UserRequest $request)
    {
        $validatedData = $request->validated();

        // Access individual fields
        $name = $validatedData['name'];
        $email = $validatedData['email'];
        $password = $validatedData['password'];


        User::create([
            "name"=>$name,
            "email"=>$email,
            "password"=>Hash::make($password),
        ]);

        return  $this->successResponse(null,'user created successfully');
    }
    //Login API (POST,from data)
    function login(Request $request)
    {
        // data validation
        $request->validate([
            "email"=>"required|email",
            "password"=>"required"
        ]);
        //JWTAuth and attempt
        $token = JWTAuth::attempt([
            "email"=>$request->email,
            "password"=>$request->password,
        ]);
        if(!empty($token))
        {
            return $this->successResponse(null, "user Logged in successfully",$token);
        }
        else{
            return  $this->errorResponse("email or password does not correct",404);
        }
    }
    //Profile API (GET)
    function profile()
    {
        $userData = auth()->user();
        return $this->successResponse($userData,'profile data');
    }
    //Refresh Token API (GET)
    function refreshToken()
    {
        $newToken = auth()->refresh();
        return  $this->successResponse(null,'new access token generated',$newToken);
    }
    //Logout Token API (GET)
    function logout()
    {
        auth()->logout();
        return $this->successResponse(null,'user logged out successfully',);
    }
}
