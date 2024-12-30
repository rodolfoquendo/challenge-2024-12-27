<?php

namespace App\Models;

use App\Services\DomainService;
use App\Services\EmailService;
use App\Traits\Blacklistable;
use App\Traits\Cached;
use App\Traits\Verifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use \Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\SoftDeletes;

/**
 * This is intended to be modified only by database and migrations
 * 
 * @property int    $id
 * @property int    $user_id
 * @property int    $gender_id
 * @property string $cod
 * @property string $title
 * @property string $starts_at
 * @property string $xends_at
 * @property float  $entry_fee
 * @property int    $max_winners
 * @property int    $max_participants
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 *
 * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
 * @copyright 2024 Rodolfo Oquendo
 */
class Tournament extends Model
{
    use HasFactory;

    protected $table = 'tournaments';
    
    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'max_winners' => 1,
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class, 'gender_id');
    }

    public function tournamentParticipants()
    {
        return $this->hasMany(TournamentParticipant::class, 'tournament_id');
    }
}
