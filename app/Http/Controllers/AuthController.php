<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'fullName' => 'required|string',
                'email' => 'required|string|unique:users',
                'password' => 'required|string|min:6',
                'userType' => 'required|string',
            ]);
            $fullName = request()->fullName;
            $email = request()->email;
            $password = Hash::make($request->input('password'));
            $userType = request()->userType;
            $user = new User;
            $user->fullName = $fullName;
            $user->email = $email;
            $user->password = $password;
            $user->userType = $userType;
            $user->save();
            return response([
                'User' => $user,
                'status' => true,
                'stateNum' => 200,
                'message' => 'done'
            ], 200);
        } catch (\Exception $ex) {
            return response([
                'status' => false,
                'stateNum' => 400,
                'message' => 'Error Register'
            ], 400);
        }
    }
    public function login(Request $request)
    {
        try {
            $this->validate($request, [
                'email' => 'required',
                'password' => 'bail|required|min:8'
            ]);
            if (Auth::attempt($request->only('email', 'password'))) {
                $user = Auth::user();
                // if ($user->role->isAdmin == 0) {
                //     Auth::logout();
                //     return response([
                //         'message' => 'Incorrect login details!'
                //     ], Response::HTTP_UNAUTHORIZED);
                // }
                $token = $user->createToken('token')->plainTextToken;

                $cookie = cookie('jwt', $token, 60 * 24); // 1 day
                return response([
                    'Token' => $token,
                    'status' => true,
                    'stateNum' => 200,
                    'message' => 'done'
                ])->withCookie($cookie);
            } else {
                return response([
                    'message' => 'Invalid credentials!'
                ], Response::HTTP_UNAUTHORIZED);
            }
        } catch (\Exception $ex) {
            return response([
                'status' => false,
                'stateNum' => 400,
                'message' => 'Error Login'
            ], 400);
        }
    }
    public function user()
    {
        return Auth::user();
    }
    public function logout()
    {
        $cookie = Cookie::forget('jwt');
        return response([
            'message' => 'Successfully logged out'
        ])->withCookie($cookie);
    }
}
