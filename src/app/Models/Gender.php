<?php

namespace App\Models;

use App\Services\DomainService;
use App\Services\EmailService;
use App\Traits\Blacklistable;
use App\Traits\Cached;
use App\Traits\Verifiable;
use \Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\SoftDeletes;

/**
 * This is intended to be modified only by database and migrations
 * 
 * @property int    $id
 * @property int    $cod
 * @property int    $title
 *
 * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
 * @copyright 2024 Rodolfo Oquendo
 */
class Gender extends Model
{
    use Cached;

    const F = 1;
    const M = 2;

    protected $table = 'gender';

    public function participants()
    {
        return $this->hasMany(Participant::class, 'gender_id');
    }
}
