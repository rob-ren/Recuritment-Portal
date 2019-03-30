<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $role_model;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->role_model = new Role();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = $this->role_model->getRoles();
        return view('roleList', array(
          'roles' => $roles));
    }

    public function store(Request $request)
    {
        $currentTime = new \DateTime();
        $role = array(
          'name' => $request->name,
          'created_at' => $currentTime,
          'updated_at' => $currentTime
        );

        if ($this->role_model->createRole($role)) {
            return redirect()->back()->with('role_success', 'Role created successfully');
        }
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $currentTime = new \DateTime();
        $role = array(
          'id' => $id,
          'name' => $request->name,
          'updated_at' => $currentTime
        );
        if ($this->role_model->updateRole($role)) {
            return redirect()->back()->with('role_success', 'Role updated successfully');
        }
        return redirect()->back();
    }
}
