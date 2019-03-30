<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'name', 'email', 'password', 'role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
      'password', 'remember_token',
    ];

    public function getUsers()
    {
        $users = DB::select('select * from users');
        return $users;
    }

    public function createUser($user)
    {
        $user_id = DB::table('users')->insertGetId($user, 'id');
        return $user_id;
    }

    public function updateUser($user)
    {
        DB::table('users')
          ->where('id', $user['id'])
          ->update($user);
        return $user;
    }
}
