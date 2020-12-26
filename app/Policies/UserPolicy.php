<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * 用户编辑权限设置
     * @author 宋佳
     * @param User $currentUser
     * @param User $user
     * @return bool
     */
    public function update(User $currentUser, User $user)
    {
        //当前用户必须跟编辑的用户是同一个人
        return $currentUser->id === $user->id;
    }


    /**
     * 删除用户权限设置
     * @author 宋佳
     * @param User $currentUser
     * @param User $user
     * @return bool
     */
    public function destroy(User $currentUser, User $user)
    {
        //当前用户必须得是管理员并且删除的用户不能是自己
        return $currentUser->is_admin && $currentUser->id !== $user->id;
    }
}
