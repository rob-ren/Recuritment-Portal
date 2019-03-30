<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Role extends Model
{
    protected $table = 'roles';

    public function getRoles()
    {
        $roles = DB::select('select * from roles order by name');
        return $roles;
    }

    public function createRole($role)
    {
        $role_id = DB::table('roles')->insertGetId($role, 'id');
        return $role_id;
    }

    public function updateRole($role)
    {
        DB::table('roles')
          ->where('id', $role['id'])
          ->update($role);
        return $role;
    }
}
