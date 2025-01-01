<?php 
namespace App\Exceptions;

class UserNotSet extends BaseException{
    protected $code = 401;
    protected $message = 'Unauthorized';
}