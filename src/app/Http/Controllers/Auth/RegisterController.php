<?php

namespace App\Http\Controllers\Auth;

use App\Domain\User\User;
use App\Domain\User\UserRepository;
use App\Domain\User\Department;
use App\Domain\User\DepartmentRepository;
// use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            // 'department' => 'required|int|min:1'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data) : User
    {
        $department = new Department(null, 'namae', 'sectionName', 'comment');
        $user = new User(
            null,
            $data['name'],
            $department,
            $data['email'],
            Hash::make($data['password']),
            1
        );

        // confirmation of whether the department exists or not.
        $depDepository = app()->make(DepartmentRepository::class);
        if ($depDepository->exists('namae') === false) {
            $depDepository->save($department);
        }

        // registration
        $uid = app()->make(UserRepository::class)->save($user);

        return new User(
            $uid,
            $user->getName(),
            $user->getDepartment(),
            $user->getEmail(),
            $user->getPassword(),
            $user->getRole()
        );
    }

    protected function registered(Request $request, $user)
    {
        return $user;
    }
}
