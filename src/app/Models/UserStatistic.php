<?php

namespace App\Models;

use \Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\SoftDeletes;
use \Illuminate\Support\Facades\Cache;

/**
 * @property int    $id
 * @property int    $user_id
 * @property string $date
 * @property int    $tournaments
 * @property int    $participants
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 *
 * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
 * @copyright 2024 Rodolfo Oquendo
 */
class UserStatistic extends Model
{
    use SoftDeletes;

    protected $table = 'user_statistics';
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
