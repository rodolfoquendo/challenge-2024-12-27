<?php

namespace App\Traits;

use DateTime;
use Illuminate\Http\Response;

trait Blacklistable 
{
    public function isBlacklisted(): bool
    {
        if(!is_null($this->blacklist_expires_at) && !is_null($this->blacklisted_at)){
            $this->blacklisted_at = strtotime($this->blacklist_expires_at) < time() ? null : $this->blacklisted_at;
            $this->save();
        }
        return !is_null($this->blacklisted_at);
    }

    public function blacklist(null|string|int|DateTime $expires_at = null, string $reason = null): bool
    {
        $wasBlacklisted = $this->isBlacklisted();
        $dateFormat = 'Y-m-d H:i:s';
        $expires_at = $expires_at instanceof DateTime ? $expires_at->format($dateFormat) : $expires_at;
        $expires_at = \is_int($expires_at) ? date($dateFormat, $expires_at) : $expires_at;
        $this->blacklist_expires_at = $expires_at;
        $this->blacklisted_at = !$wasBlacklisted ? date('Y-m-d H:i:s') : $this->blacklisted_at;
        $this->blacklist_cnt += !$wasBlacklisted ? 1 : 0 ;
        return $this->save();
    }

    public function unBlacklist(): bool
    {
        if(!$this->isBlacklisted()){
            return true;
        }
        $this->blacklist_expires_at = null;
        $this->blacklisted_at = null;
        return $this->save();
    }
}