<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;

class UserController extends BaseController
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
    
    public function search(Request $request)
    {
        $searchCondition = [
            'name' => $request->input('name', ''),
            'email' => $request->input('email', ''),
            'group_role' => $request->input('group', ''),
            'is_active' => $request->input('status', ''),
            'page' => $request->input('page', ''),
        ];
        $users = $this->userRepository->searchUser($searchCondition);
        return $this->sendResponse($users, 'Users list');
    }

    public function lockUser(Request $request)
    {
        $action = $request->input('status') == 1 ? 'Mở khóa' : 'Khóa';

        $result = $this->userRepository->updateUserStatus($request->input('id'), $request->input('status'));
        if ($result) {
            return $this->sendResponse([], trans('message.users.action_success', ['action' => $action]));
        }
        return $this->sendError(trans('message.users.action_failed', ['action' => $action]));
    }

    public function deleteUser(Request $request)
    {
        $result = $this->userRepository->deteteUser($request->input('id'));
        if ($result) {
            return $this->sendResponse([], trans('message.users.action_success', ['action' => 'Delete']));
        }
        return $this->sendError(trans('message.users.action_failed', ['action' => 'Delete']));
    }

    public function storeUser(UserCreateRequest $request)
    {
        $params = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'group_role' => $request->input('group'),
            'is_active' => $request->input('status', 0),
        ];
        $result = $this->userRepository->createUser($params);
        if ($result) {
            return $this->sendResponse([], trans('message.users.action_success', ['action' => 'Thêm mới']));
        }
        return $this->sendError(trans('message.users.action_failed', ['action' => 'Thêm mới']));
    }

    public function saveUser(UserUpdateRequest $request)
    {
        $params = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'group_role' => $request->input('group'),
            'is_active' => $request->input('status', 0),
        ];
        $id = $request->input('id');

        //validate unique
        if ($this->userRepository->isDuplicateUser($id, $params['email'])) {
            return $this->sendError('', ['email' => [trans('validation.custom.email.unique', ['attribute' => 'Email'])]]);
        }

        $result = $this->userRepository->updateUser($id, $params);
        if ($result) {
            return $this->sendResponse([], trans('message.users.action_success', ['action' => 'Cập nhật']));
        }
        return $this->sendError(trans('message.users.action_failed', ['action' => 'Cập nhật']));
    }
}
