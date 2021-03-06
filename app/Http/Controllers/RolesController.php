<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
// use Spatie\Permission\Models\Permission;

class RolesController extends Controller
{
    public function __construct(Role $role){
        $this->role = $role;
        $this->middleware(['role:admin']);
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
    public function updateRole($id,Request $request)
    {
        try {
            // $this->validate($request, [
            //     'name' => 'required|string|unique:roles',
            //     'permissions' => 'required'
            // ]);
            $role = Role::findOrFail($id);
            if (isset($role)) {
                $role->name = $request->name;
                if($request->has('permissions')){
                    $rolePermissions = $role->getPermissionNames();
                    foreach($rolePermissions as $permission){
                        $role->revokePermissionTo($permission);
                    }
                    $role->givePermissionTo($request->permissions);
                }
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
            return $ex->getMessage();
            return response([
                'status' => false,
                'stateNum' => 400,
                'message' => 'Error! Roles doesnt exist yet'
            ], 400);
        }
    }
}
