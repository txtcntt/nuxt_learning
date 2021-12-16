<?php

namespace App\Repositories;

/**
 * Interface UserRepositoryInterface
 *
 * @package App\Repositories
 */
interface UserRepositoryInterface extends RepositoryInterface
{    
    /** 
     * Get user information by email
     * 
     * @param $email
     * 
     * @return model
     */
    public function getUserByEmail($email);

    /**
     * Update log info before user login success
     * 
     * @param $userId
     * @param $data array
     * 
     * @return boolean (true: update success, false: update failed)
     */
    public function updateLogin($userId, $data = []);

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
    
    public function searchUser($params = []);
    /**
     * Update user status to mst_users.is_active
     * 
     * @param $userId
     * @param $status (0: inactive, 1: active)
     * @return boolean
     */
    public function updateUserStatus($userId, $status);

    /**
     * Delete user. Update to mst_users.is_delete = 1
     * 
     * @param $userId
     * @return boolean
     */
    public function deteteUser($userId);

    /** 
     * Insert new user information
     * 
     * @param $params
     * @return boolean
     */
    public function createUser($params);

    /** 
     * Update user information
     * 
     * @param $params
     * @return boolean
     */
    public function updateUser($id, $params);

    /**
     * Check duplicate user
     * 
     * @param $id
     * @param $email
     * 
     * @return boolean true: is duplicate, false: not duplicate 
     */
    public function isDuplicateUser($id, $email);
}