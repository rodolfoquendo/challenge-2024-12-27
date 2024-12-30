<?php 
namespace App\Models;

use \App\Traits\Cached;
use \App\Traits\ModelEnabled;
use \Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\SoftDeletes;
use \Illuminate\Support\Facades\Cache;

/**
 * This is intended to be modified only by database and migrations
 * 
 * @property int    $id
 * @property string $cod
 * @property string $title
 * @property int    $enabled
 * @property string $description
 * @property float  $price_monthly
 * @property float  $price_yearly
 * @property int    $tournaments
 * @property int    $participants
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 *
 * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
 * @copyright 2024 Rodolfo Oquendo
 */
class Plan extends Model{
    use SoftDeletes, 
        ModelEnabled, 
        Cached;

    const UNLIMITED  = 1;
    const FREE       = 2;
    const STARTER    = 3;
    const SMALL      = 4;

    protected $table = 'plans';

    public function users(){
        return $this->hasMany(User::class, 'plan_id');
    }
}
    
