<?php 
namespace App\Services\Validators;

use App\Services\ServiceBase;

class EmailValidationService extends ServiceBase
{
    public function sanitize(string $email): string
    {
        return trim(\strtolower($email));
    }
    public function validate(string $email): bool
    {
        try {
            $email = $this->sanitize($email);
            if(!\filter_var($email, \FILTER_VALIDATE_EMAIL)){
                return false;
            }
            list($username, $domain) = \explode('@', $email);
            if(!\dns_check_record($domain)){
                return false;
            }   
        } catch ( \Exception $e ) {
            return false;
        }
        return true;
    }
}