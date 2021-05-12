<?php

namespace app\model;

use think\Model;

/**
 * Class User
 * @package app\model
 */
class User extends Model
{
    /**
     * @var string
     */
    protected $table = 'users';

    /**
     * @var string
     */
    protected $pk = 'id';
}
