<?php 
namespace App\Exceptions;

class PlanLimitNotValid extends BaseException{
    protected $code = 422;
}