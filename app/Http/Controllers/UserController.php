<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $user_model;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');
        $this->user_model = new User();
    }

    public static function EnumToArray($class)
    {
        $reflect = new \ReflectionClass ($class);
        $constants = $reflect->getConstants();
        return $constants;
    }

    protected function validatorCreate(array $data, $message = null)
    {
        return Validator::make($data, [
          'name' => 'required|string|max:255',
          'email' => 'required|string|email|max:255|unique:users',
          'password' => 'required|string|min:6',
          'password_confirmation' => 'required|same:password'
        ], $message);
    }

    public function messages()
    {
        return [
          'name.required' => 'You must input name',
          'email.required' => 'You must input email',
          'email.unique' => 'This email already existed.',
          'password.required' => 'You must input password',
          'password_confirmation.same' => 'Input password is not same',
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->user_model->getUsers();
        $user_role_array = $this->EnumToArray('App\Enum\UserRole');
        return view('userList', array(
          'user_role_array' => $user_role_array,
          'users' => $users));
    }

    public function store(Request $request)
    {
        $currentTime = new \DateTime();

        $this->validatorCreate($request->all(), $this->messages())->validate();

        $user = array(
          'name' => $request->name,
          'email' => $request->email,
          'password' => Hash::make($request->password),
          'role' => $request->role,
          'status' => $request->status,
          'created_at' => $currentTime,
          'updated_at' => $currentTime
        );

        if ($this->user_model->createUser($user)) {
            return redirect()->back()->with('user_success', 'User created successfully');
        }
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $currentTime = new \DateTime();

        $user = array(
          'id' => $id,
          'name' => $request->name,
          'email' => $request->email,
          'role' => $request->role,
          'status' => $request->status,
          'updated_at' => $currentTime
        );
        if ($this->user_model->updateUser($user)) {
            return redirect()->back()->with('user_success', 'User updated successfully');
        }
        return redirect()->back();
    }
}
