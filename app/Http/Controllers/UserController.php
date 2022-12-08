<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
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
            "password" => Hash::make($userData['name']),
        ]);
        return response($user->toJson(), 201);
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
        return response(['user' => $user, 'token' => $token]);
    }
}
