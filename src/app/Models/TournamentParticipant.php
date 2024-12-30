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
 * @property int    $tournament_id
 * @property int    $participant_id
 * @property bool   $entry_fee_paid
 * @property bool   $winner
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 *
 * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
 * @copyright 2024 Rodolfo Oquendo
 */
class TournamentParticipant extends Model
{

    protected $table = 'tournament_participants';

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'entry_fee_paid' => 0,
        'winner' => 0,
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class, 'tournament_id');
    }

    public function participant()
    {
        return $this->belongsTo(Participant::class, 'participant_id');
    }
}
