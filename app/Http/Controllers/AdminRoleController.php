<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;


class AdminRoleController extends Controller
{
   public function index()
   {
       $roles = Role::all();
       
       return $this->respondWithSuccess($roles);
   }
    
   
   public function show($id)
   {
       $role = Role::findOrFail($id);
       
       return $this->respondWithSuccess($role);
   }

   public function store(Request $request)
   {
       $request->validate(['name' => 'required']);
          
       $role = Role::create(['name' => $request->name]);
       
       return $this->respondWithSuccess($role);
   } 

   public function update($id , Request $request)
   {
       $role = Role::findOrFail($id);
       $role->update(['name' => $request->name]);
       
       return $this->respondWithSuccess($role);
   }

   public function destroy($id)
   {
       $role = Role::findOrFail($id);
       $role->delete();
       return $this->respondWithSuccess($role);
   }

   public function assign_role_to_admin(Request $request)
   {
       $admin = Admin::findOrFail($request->admin_id);
       $role = Role::findOrFail($request->role_id);
       $admin->assignRole($role);
        return $this->respondWithSuccess($role,$admin);
   }  

   public function remove_role_from_admin(Request $request)
   {
       $admin = Admin::findOrFail($request->admin_id);
       $role = Role::findOrFail($request->role_id);
       $admin->removeRole($role);
        return $this->respondWithSuccess($role,$admin);
   }  
   
}