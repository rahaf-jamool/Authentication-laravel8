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
                'email' => 'required|email|unique:users',
                'password' => 'alpha_num|bail|required|min:8',
                'role' => 'required',
                'permissions' => 'required'
            ]);
            $user = new User;
            $user->fullName = request()->fullName;
            $user->email = request()->email;
            $user->password = Hash::make($request->password);
            $user->assignRole($request->role);
            if($request->has('permissions')){
                $user->givePermissionTo($request->permissions);
            }
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
