<?php

namespace app\service;

use app\model\User;

/**
 * Class UserService
 * @package app\service
 */
class UserService
{
    /**
     * @var \app\model\User
     */
    private User $user;

    /**
     * UserService constructor.
     * @param \app\model\User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
