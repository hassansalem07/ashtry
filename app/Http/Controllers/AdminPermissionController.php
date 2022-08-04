<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminPermissionController extends Controller
{
    public function index()
    {
        $permission = Permission::all();
        
        return $this->respondWithSuccess($permission);
    }
     
    
    public function show($id)
    {
        $permission = Permission::findOrFail($id);
        
        return $this->respondWithSuccess($permission);
    }
 
    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);
           
        $permission = Permission::create(['name' => $request->name]);
        
        return $this->respondWithSuccess($permission);
    } 
 
    public function update($id , Request $request)
    {
        $permission = Permission::findOrFail($id);
        $permission->update(['name' => $request->name]);
        
        return $this->respondWithSuccess($permission);
    }
 
    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();
        return $this->respondWithSuccess($permission);
    }

    public function assign_permission_to_role(Request $request)
    {
        $permission = Permission::findOrFail($request->permission_id);
        $role = Role::findOrFail($request->role_id);
        $role->givePermissionTo($permission);
        return $this->respondWithSuccess($role,$permission);
    }  

    public function remove_permission_from_role(Request $request)
    {
        $permission = Permission::findOrFail($request->permission_id);
        $role = Role::findOrFail($request->role_id);
        $role->revokePermissionTo($permission);
        return $this->respondWithSuccess($role,$permission);
    }  
}