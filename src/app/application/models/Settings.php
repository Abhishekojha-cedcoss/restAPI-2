<?php

use Phalcon\Mvc\Model;

/**
 * Settings class
 * 
 * Implements settings table
 */
class Settings extends Model
{
    public $title_optimization;
    public $default_price;
    public $default_stock;
    public $default_zipcode;
}