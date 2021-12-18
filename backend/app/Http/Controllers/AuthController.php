<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * UserController constructor.
     * 
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    
    public function register(UserRegisterRequest $request)
    {
        $user = User::create([
            'email' => $request->email,
            'name' => $request->name,
            'password' => bcrypt($request->password),
        ]);

        if (!$token = auth()->attempt($request->only(['email', 'password']))) {
            return abort(401);
        };

        return (new UserResource($request->user()))->additional([
            'meta' => [
                'token' => $token,
            ],
        ]);
    }

    public function login(UserLoginRequest $request)
    {
        try {
            $loginParams = $request->only(['email', 'password']);
            $loginParams['is_active'] = 1;
            if (!$token = auth()->attempt($loginParams)) {
                $errors = [];
                $userInfo = $this->userRepository->getUserByEmail($loginParams['email']);
                if (empty($userInfo)) {
                    $errors['email'] = [trans('auth.account_not_exists')];
                } else {
                    //check password correct
                    if (!Hash::check($loginParams['password'], $userInfo->password)) {
                        $errors['password'] = [trans('auth.password')];
                    } else {
                        $errors['common'] = [trans('auth.account_active')];
                    }
                }
                return response()->json([
                    'errors' => $errors,
                ], 422);
            };

            return (new UserResource($request->user()))->additional([
                'meta' => [
                    'token' => $token,
                ],
            ]);
        } catch (Exception $ex) {
            Log::error($ex->getMessage(), [
                'file' => $ex->getFile(),
                'line' => $ex->getLine(),
                'trace' => $ex->getTrace(),
            ]);
            return response()->json(['errors' => ['common' => trans('auth.authorize_failed')]]);
        }
    }

    public function user(Request $request)
    {
        return  new UserResource($request->user());
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'success']);
    }
}
