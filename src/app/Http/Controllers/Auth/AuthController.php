<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
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
            // 422 abort.
            abort('422', "Its department ID doesn't exist.");
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
        if ($uid === null) {
            // nullの場合
            // 失敗ということで422
            abort('422', "The email address seems to be duplicated.");
        }

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

    /**
     * 認証する（ログイン）
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function authenticate(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        try {
            /**
             * $token false|string
             */
            $token = JWTAuth::attempt($credentials);

            if ($token === false) {
                return response()->json(['message' => 'Invalid Credentials.'], 422);
            }
        } catch (JWTException $e) {
            return response()->json(['message' => 'Server Error.'], 500);
        }

        // emailで認証できた = emailでuser取得できる(non-null)
        $email = $request->get('email');

        /** @var \App\Domain\User\User */
        $user = $this->userRepository->findByEmail($email);
        return response()->json(['message' => 'You are successfully logged in!','token' => $token]);
    }
}
