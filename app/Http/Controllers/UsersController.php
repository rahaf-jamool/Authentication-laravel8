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
            $user_id = User::latest()->get();
            $users= User::find($user_id);
            $users = User::with('roles')->get();
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
            return $ex->getMessage();
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
            return $ex->getMessage();
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
                'fullName' => 'required|string',
                'email' => 'required|email|unique:users,email,'.$id,
                'password' => 'alpha_num|bail|required|min:8',
                'role' => 'required',
                'permissions' => 'required'
            ]);
            $user = User::findOrFail($id);
            if (isset($user)) {
                $user->fullName = $request->fullName;
                $user->email = $request->email;
                if($request->has('password')){
                    $user->password = Hash::make($request->password);
                }
                if($request->has('role')){
                    $userRole = $user->getRoleNames();
                    foreach($userRole as $role){
                        $user->removeRole($role);
                    }
                    $user->assignRole($request->role);
                }
                if($request->has('permissions')){
                    $userPermissions = $user->getPermissionNames();
                    foreach($userPermissions as $permission){
                        $user->revokePermissionTo($permission);
                    }
                    $user->givePermissionTo($request->permissions);
                }
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
            return $ex->getMessage();
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
