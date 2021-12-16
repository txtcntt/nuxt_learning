<?php

namespace App\Repositories;

use App\Models\MstUser;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserRepository
 *
 * @package App\Repositories
 */
class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * @var User
     */
    protected $model;

    /**
     * UserRepository constructor.
     *
     * @param User $model
     */
    public function __construct(MstUser $model)
    {
        parent::__construct($model);
    }

    /** 
     * Get user information by email
     * 
     * @param $email
     * 
     * @return model
     */
    public function getUserByEmail($email)
    {
        $userInfo = $this->model->where('email', $email)->first();
        return $userInfo ? $userInfo : [];
    }

    /**
     * Update log info before user login success
     * 
     * @param $userId
     * @param $data array
     * 
     * @return boolean (true: update success, false: update failed)
     */
    public function updateLogin($userId, $data = [])
    {
        $attributes = ['last_login_at' => now(), 'last_login_ip' => $data['ip'], 'updated_at' => now()];
        if (isset($data['remember']) && !$data['remember']) {
            $attributes['remember_token'] = '';
        }
        return $this->update($userId, $attributes);
    }

    /** 
     * Get users information with condition and paging
     * @param $params = [
     *  'name' => user name,
     *  'email' => user email,
     *  'group_role' => ,
     *  'is_active' => (1: active, 0: inactive)
     * ]
     * @return Pagination
     */
    public function searchUser($params = [])
    {
        $fields = ['id', 'name', 'email', 'group_role', 'is_active'];
        $currentPage = 1;
        $conditions = [];
        if (!empty($params)) {
            if (isset($params['name']) && $params['name'] != '') {
                $conditions[] = ['name', 'like', '%' . $params['name'] . '%'];
            }
            if (isset($params['email']) && $params['email'] != '') {
                $conditions[] = ['email', 'like', '%' . $params['email'] . '%'];
            }
            if (isset($params['group_role']) && $params['group_role'] != '') {
                $conditions[] = ['group_role', '=', $params['group_role']];
            }
            if (isset($params['is_active']) && ($params['is_active'] == '0' || $params['is_active'] == '1')) {
                $conditions[] = ['is_active', '=', $params['is_active']];
            }
            if (isset($params['page']) && $params['page'] != '') {
                $currentPage = $params['page'];
            }
        }
        if (empty($conditions)) {
            return $this->model->available()->orderBy('created_at', 'desc')
                ->paginate(config('constants.item_per_page'), $fields, 'page', $params['page']);
        }
        return $this->model->available()->where($conditions)->orderBy('created_at', 'desc')
            ->paginate(config('constants.item_per_page'), $fields, 'page', $currentPage);
    }

    /**
     * Update user status to mst_users.is_active
     * 
     * @param $userId
     * @param $status (0: inactive, 1: active)
     * @return boolean
     */
    public function updateUserStatus($userId, $status)
    {
        $attributes = ['is_active' => $status, 'updated_at' => now()];
        return $this->update($userId, $attributes);
    }

    /**
     * Delete user. Update to mst_users.is_delete = 1
     * 
     * @param $userId
     * @return boolean
     */
    public function deteteUser($userId)
    {
        $attributes = ['is_delete' => 1, 'updated_at' => now()];
        return $this->update($userId, $attributes);
    }

    /** 
     * Insert new user information
     * 
     * @param $params
     * @return boolean
     */
    public function createUser($params)
    {
        $params['password'] = Hash::make($params['password']);
        $params['created_at'] = now();
        return $this->create($params);
    }

    /** 
     * Update user information
     * 
     * @param $params
     * @return boolean
     */
    public function updateUser($id, $params)
    {
        $params['password'] = Hash::make($params['password']);
        $params['updated_at'] = now();
        return $this->update($id, $params);
    }

    /**
     * Check duplicate user
     * 
     * @param $id
     * @param $email
     * 
     * @return boolean true: is duplicate, false: not duplicate 
     */
    public function isDuplicateUser($id, $email)
    {
        $count = $this->model->where([
            ['email', '=', $email],
            ['id', '<>', $id]
        ])->count('id');
        return $count > 0;
    }
}
