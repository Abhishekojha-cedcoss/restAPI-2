<?php

use Phalcon\Mvc\Model;

class Orders extends Model
{
    public $user_id;
    public $Product_Detail;
    public $shipping_address;
    public $status;
}