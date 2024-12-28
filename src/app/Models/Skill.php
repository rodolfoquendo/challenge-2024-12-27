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
 * @property int    $gender_id
 * @property string $cod
 * @property string $title
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 *
 * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
 * @copyright 2024 Rodolfo Oquendo
 */
class Skill extends Model
{
    use Cached;

    protected $table = 'skills';
    
    public function gender()
    {
        return $this->belongsTo(Gender::class, 'gender_id');
    }

    public function participants()
    {
        return $this->hasMany(ParticipantSkill::class, 'skill_id');
    }
}
