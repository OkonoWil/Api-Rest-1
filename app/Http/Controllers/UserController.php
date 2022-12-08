<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use \Illuminate\Http\Response;
use Illuminate\Console\Parser;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{

    public function register(Request $request)
    {
        try {
            $userData = $request->validate(
                [
                    'name' => ["required", "string"],
                    'email' => ["required", "email", "unique:users,email"],
                    'password' => ["required", "min:8"]
                ]
            );
        } catch (ValidationException $validatorError) {
            return response($validatorError->errors(), 422);
        }
        $user = User::create([
            "name" => $userData['name'],
            "email" => $userData['email'],
            "password" => Hash::make($userData['password']),
        ]);
        return response($user->toJson(), 201);
        // $this->validate($request, [
        //     'name' => ["required", "string"],
        //     'email' => ["required", "email", "unique:users,email"],
        //     'password' => ["required", "min:8"]
        // ]);
        // $user = User::create([
        //     "name" => $request->name,
        //     "email" => $request->email,
        //     "password" => Hash::make($request->password),
        // ]);
        // return response($user, 201);
    }

    public function login(Request $request)
    {
        try {
            $userData = $request->validate(
                [
                    'email' => ["required", "email"],
                    'password' => ["required", "min:8"]
                ]
            );
        } catch (ValidationException $validatorError) {
            return response($validatorError->errors(), 422);
        }
        $user = User::where("email", $userData['email'])->first();
        if (!$user) return response(['email' =>  "not user with this email"], 401);
        if (!Hash::check($userData['password'], $user->password)) return response(['password' => 'password is incorrect'], 401);
        $token = $user->createToken("CLE_SECRETE")->plainTextToken;
        // $token = $user->createToken($request->device_name)->plainTextToken;
        return response(['user' => $user, 'token' => $token]);
    }

    public function logout(Request $request)
    {
        // search the bearer token
        $value = $request->bearerToken();
        // dd(explode("|", $value)[0]);
        // $token = $request->user()->tokens->find(explode("|", $value)[0]);
        // dd($token);
        // dd(auth()->user()->tokens->where('id', $token->id)->first());
        // we delete the token parse in the request
        auth()->user()->tokens->where('id', explode("|", $value)[0])->first()->delete();
        return response(["message" => "logout"], 200);
    }
    public function allLogout()
    {
        // we delete all the tokens of user 
        auth()->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });
        return response(["message" => "logout"], 200);
    }
}
