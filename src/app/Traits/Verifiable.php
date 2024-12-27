<?php
namespace App\Traits;

trait Verifiable 
{

    /**
     * Check if the model instance is verified
     * Verifications lasts 1 year
     *
     * @return bool If is verified
     *
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @copyright 2024 Afluenta
     */
    public function isVerified(): bool
    {
        $this->verified_at = is_null($this->verified_at) || (strtotime($this->verified_at) + 365 * 24 * 60 * 60) < time() ? null : $this->verified_at;
        return !is_null($this->verified_at);
    }

    /**
     * Verifies the current model instance
     *
     * @return bool If is verified
     *
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @copyright 2024 Afluenta
     */
    public function verify(): bool
    {
        $this->verified_at = !$this->isVerified() ? date('Y-m-d H:i:s') : $this->verified_at;
        $this->save();
        return $this->isVerified();
    }

    /**
     * Un-verifies the current model instance
     * (for example if we detect that a domain no longer works)
     *
     * @return bool If is verified
     *
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @copyright 2024 Afluenta
     */
    public function unVerify(): bool
    {
        $this->verified_at = null;
        $this->save();
        return $this->isVerified();
    }
}