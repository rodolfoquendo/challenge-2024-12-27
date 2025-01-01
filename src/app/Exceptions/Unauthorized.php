<?php 
namespace App\Exceptions;

class Unauthorized extends BaseException{
    protected $code = 401;
}