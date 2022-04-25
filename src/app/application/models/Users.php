<?php

use Phalcon\Mvc\Model;

/**
 * Users class
 * 
 * implements Users table
 */
class Users extends Model
{
    public $name;
    public $email;
    public $password;
    public $role;
    public $token;
}
