<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Controllers\Controller;
use App\Domain\User\User;
use App\Domain\User\UserRepository;
use JWTAuth;
use App\Domain\User\DepartmentRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /** @var UserRepository */
    private $userRepository;

    /** @var DepartmentRepository */
    private $departmentRepository;

    /**
     * AuthController constructor.
     * @param UserRepository $userRepository
     * @param Request $request
     */
    public function __construct(
        UserRepository $userRepository,
        DepartmentRepository $departmentRepository,
        Request $request
    )
    {
        $this->userRepository = $userRepository;
        $this->departmentRepository = $departmentRepository;

        JWTAuth::setRequest($request);
    }

    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $departmentId = $request->get('department');

        // TODO: Departmentに存在しない場合のエラーの対応を行う

        // confirmation of whether the department exists or not.
        if ($this->departmentRepository->existsById($departmentId) === false)
        {
            // If Id doesn't exists.
            // 500 abort.
            abort('500');
        }

        $department = $this->departmentRepository->findById($departmentId);
        $user = new User(
            null,
            $request->get('name'),
            $department,
            $request->get('email'),
            Hash::make($request->get('password')),
            1,
            ''
        );

        // registration
        $uid = app()->make(UserRepository::class)->save($user);

        return response()->json((new User(
            $uid,
            $user->getName(),
            $user->getDepartment(),
            $user->getEmail(),
            $user->getPassword(),
            $user->getRole(),
            $user->getRememberToken()
        ))->toArray());
    }
}
