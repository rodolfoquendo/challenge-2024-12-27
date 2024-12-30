<?php 
namespace App\Exceptions;

class UserNotSet extends \Exception{
    protected $code = 401;
    protected $message = 'Unauthorized';
}