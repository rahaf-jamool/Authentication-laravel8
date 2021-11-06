<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function getAllUsers()
    {
        try {
            $users = User::latest()->get();

            if (count($users) > 0) {
                return response()->json([
                    'Users' => $users,
                    'status' => true,
                    'stateNum' => 200,
                    'message' => 'done'
                ], 200);
            } else {
                return response([
                    'Users' => $users,
                    'status' => true,
                    'stateNum' => 401,
                    'message' => 'This User not found'
                ], 401);
            }
        } catch (\Exception $ex) {
            return response([
                'status' => false,
                'stateNum' => 401,
                'message' => 'Error! Users doesnt exist yet'
            ], 401);
        }
    }
    public function getByIdUser($id)
    {
        try {
            $user = User::find($id);
            if (isset($user)) {
                return response([
                    'User' => $user,
                    'status' => true,
                    'stateNum' => 200,
                    'message' => 'done'
                ], 200);
            } else {
                return response([
                    'status' => true,
                    'stateNum' => 401,
                    'message' => 'This User not found'
                ], 401);
            }
        } catch (\Exception $ex) {
            return response([
                'status' => false,
                'stateNum' => 401,
                'message' => 'Error! Users doesnt exist yet'
            ], 401);
        }
    }
    public function createUser(Request $request)
    {
        try {
            $this->validate($request, [
                'fullName' => 'required',
                'email' => 'bail|required|email|unique:users',
                'password' => 'bail|required|min:8',
                'userType' => 'required',
                // 'role_id' => 'required'
            ]);
            $password = Hash::make($request->password);
            $fullName = request()->fullName;
            $email = request()->email;
            $password = $password;
            $userType = request()->userType;
            // $role_id = request()->role_id;
            $user = new User;
            $user->fullName = $fullName;
            $user->email = $email;
            $user->password = $password;
            $user->userType = $userType;
            // $user->role_id = $role_id;
            // $user->roles()->sync($request->role);
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
                'stateNum' => 401,
                'message' => 'Error! Users doesnt exist yet'
            ], 401);
        }
    }
    // error
    public function updateUser(Request $request, User $user, $id)
    {
        try {
            $this->validate($request, [
                'fullName' => 'required',
                'email' => 'bail|required|email|unique:users',
                'password' => 'required|min:8',
                'userType' => 'required'
            ]);
            $user = User::find($id);
            if (isset($user)) {
                $user->update([
                    'password' => Hash::make($request->password),
                    'fullName' => $request->fullName,
                    'email' => $request->email,
                    'userType' => $request->userType,
                    // 'role_id' => $request->role_id,
                ]);
                return response([
                    'User' => $user,
                    'status' => true,
                    'stateNum' => 200,
                    'message' => 'done'
                ], 200);
            } else {
                return response([
                    'message' => 'This User not found'
                ], 401);
            }
        } catch (\Exception $ex) {
            return response([
                'status' => false,
                'stateNum' => 401,
                'message' => 'Error! Users doesnt exist yet'
            ], 401);
        }
    }
    public function deleteUser($id){
        try {
            $user = User::find($id);
            if (isset($user)) {
                $user = User::destroy($id);
                return response([
                    'status' => true,
                    'stateNum' => 200,
                    'message' => 'done'
                ],200);
            } else {
                return response([
                    'status' => true,
                    'stateNum' => 401,
                    'message' => 'This User not found'
                ],401);
            }
        } catch (\Exception $ex) {
            return response([
                'status' => false,
                'stateNum' => 401,
                'message' => 'Error! Users doesnt exist yet'
            ],401);
        }
    }
}
