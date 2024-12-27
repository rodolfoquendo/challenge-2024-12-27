<?php

namespace App\Models;

use App\Services\DomainService;
use App\Services\EmailService;
use App\Traits\Blacklistable;
use App\Traits\Verifiable;
use \Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\SoftDeletes;

/**
 * This is intended to be modified only by database and migrations
 * 
 * @property int    $id
 * @property int    $name
 * @property string $skill_level
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 *
 * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
 * @copyright 2024 Rodolfo Oquendo
 */
class Participant extends Model
{
    use SoftDeletes;

    protected $table = 'participant';

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
    ];

    public function gender()
    {
        return $this->belongsTo(Gender::class, 'gender_id');
    }
}
