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
     * @param User $currentUser
     * @param User $user
     * @return bool
     * 用户编辑越权控制
     */
    public function update(User $currentUser, User $user)
    {
        return $currentUser->id === $user->id;
    }

    /**
     * @param User $currentUser
     * @param User $user
     * @return bool
     * 用户删除授权控制
     */
    public function destroy(User $currentUser, User $user)
    {
        return $currentUser->is_admin && $currentUser->id !== $user->id;
    }

    /**
     * @param User $currentUser
     * @param User $user
     * @return bool
     * 用户关注鉴权
     */
    public function follow(User $currentUser, User $user)
    {
        // 用户自己不可关注自己
        return $currentUser->id !== $user->id;
    }
}
