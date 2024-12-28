<?php 
namespace App\Exceptions;

class PlanLimitNotValid extends \Exception{
    protected $code = 422;
}