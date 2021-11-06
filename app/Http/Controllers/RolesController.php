<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesController extends Controller
{
    public function __construct(Role $role){
        // $this->middleware('auth');
        $this->role = $role;
    }
    public function getAllRoles()
    {
        try {
            $role_id = Role::all();
            $roles= Role::find($role_id);
            $roles = Role::with('permissions')->get();
            if (count($roles) > 0) {
                return response([
                    'Roles' => $roles,
                    'status' => true,
                    'stateNum' => 200,
                    'message' => 'done'
                ], 200);
            } else {
                return response([
                    'Roles' => $roles,
                    'status' => true,
                    'stateNum' => 401,
                    'message' => 'Roles doesnt exist yet'
                ], 401);
            }
        } catch (\Exception $ex) {
            return $ex->getMessage();
            return response([
                'status' => false,
                'stateNum' => 400,
                'message' => 'Error! Roles doesnt exist yet'
            ], 400);
        }
    }
    public function assignRole(Request $request, $id)
    {
        $this->validate($request, [
            'permission' => 'required',
        ]);
        try {
            $role = Role::find($id);
            $role->update([
                'permission' => $request->permission,
            ]);
            return response([
                'Role' => $role,
                'status' => true,
                'stateNum' => 200,
                'message' => 'done'
            ], 200);
        } catch (\Exception $ex) {
            return $ex->getLine();
            return response([
                'status' => false,
                'stateNum' => 400,
                'message' => 'Error! Permissin doesnt exist yet'
            ], 400);
        }
    }
    public function getByIdRole($id)
    {
        try {
            $role = Role::find($id);
            if (isset($role)) {
                return response([
                    'role' => $role,
                    'status' => true,
                    'stateNum' => 200,
                    'message' => 'done'
                ], 200);
            } else {
                return response([
                    'Role' => $role,
                    'status' => true,
                    'stateNum' => 401,
                    'message' => 'This Role not found'
                ], 401);
            }
        } catch (\Exception $ex) {
            return response([
                'status' => false,
                'stateNum' => 400,
                'message' => 'Error! Roles doesnt exist yet'
            ], 400);
        }
    }
    public function createRole(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required|string|unique:roles',
                'permissions' => 'required'
            ]);
            $role = $this->role->create([
                'name' => $request->name,

            ]);
            if($request->has('permissions')){
                // $role=Role::find($role_id);
                $role->givePermissionTo($request->permissions);
            }
            return response([
                'Role' => $role,
                'status' => true,
                'stateNum' => 200,
                'message' => 'done'
            ], 200);
        } catch (\Exception $ex) {
            return $ex->getMessage();
            return response([
                'status' => false,
                'stateNum' => 400,
                'message' => 'Error! Roles doesnt exist yet'
            ], 400);
        }
    }
    public function updateRole($id)
    {
        try {
            $role = Role::find($id);
            if (isset($role)) {
                $name = request()->name;
                $slug = request()->slug;
                $isAdmin = request()->isAdmin;
                $role = new Role;
                $role->name = $name;
                $role->slug = $slug;
                $role->isAdmin = $isAdmin;
                $role->save();
                return response([
                    'Role' => $role,
                    'status' => true,
                    'stateNum' => 200,
                    'message' => 'done'
                ], 200);
            } else {
                return response([
                    'Role' => $role,
                    'status' => true,
                    'stateNum' => 401,
                    'message' => 'This Role not found'
                ], 401);
            }
        } catch (\Exception $ex) {
            return response([
                'status' => false,
                'stateNum' => 400,
                'message' => 'Error! Roles doesnt exist yet'
            ], 400);
        }
    }
}
