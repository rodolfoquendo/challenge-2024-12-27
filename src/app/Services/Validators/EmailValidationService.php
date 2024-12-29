<?php 
namespace App\Services\Validators;

use App\Services\ServiceBase;

/**
 * Validation for emails 
 * 
 * @todo improve
 *
 * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
 * @copyright 2024 Rodolfo Oquendo
 */
class EmailValidationService extends ServiceBase
{
    /**
     * Sanitizes the email 
     * this is MVP so 
     * @todo improve
     *
     * @param  string $email The email to be sanitized
     *
     * @return string        The email sanitized
     *
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @copyright 2024 Rodolfo Oquendo
     */
    private function sanitize(string $email): string
    {
        return trim(\strtolower($email));
    }

    /**
     * Validates an email
     * also MVP so
     * @todo improve
     *
     * @param  string $email The email to be validated
     *
     * @return bool          If is a valid email and the domain exists
     *
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @copyright 2024 Rodolfo Oquendo
     */
    public function validate(string $email): bool
    {
        $email = $this->sanitize($email);
        if(!\filter_var($email, \FILTER_VALIDATE_EMAIL)){
            return false;
        }
        list($username, $domain) = \explode('@', $email);
        return dns_check_record($domain);
    }
}