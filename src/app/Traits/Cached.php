<?php 
namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

trait Cached{

    public function save(array $options = []){
        $return     = parent::save($options);
        Cache::flush();
        return $return;  
    }

    public static function findCached($id): ?Model
    {
        $key = get_called_class() . "-" . __FUNCTION__ . "-{$id}";
        if(!Cache::has($key)){
            $all = self::getCached();
            Cache::forever($key,$all[$id]);
        }
        return Cache::get($key);
    }

    public function getTable(): string
    {
        return $this->table;
    }

    public static function getCached(
        $order_by = 'id', 
        $order_direction = 'asc', 
        $distinct = null, 
        $enabled_only = false
    ): array
    {
        $class = \get_called_class();
        $table = (new $class())->getTable();
        $key   = "{$table}-" . 
            __FUNCTION__ . 
            "-{$order_by}-{$order_direction}" . 
            (!is_null($distinct) ? "-{$distinct}" : '') . 
            (!is_null($enabled_only) ? "-enabled" : '');
        if(!Cache::has($key)){
            $data = [];
            $query = DB::table($table);
            if($enabled_only){
                $query->where('enabled',1);
            }
            if(!is_null($distinct)){
                $query->groupBy($distinct);
            }
            foreach($query->orderBy($order_by,$order_direction)->get() as $model){
                $data[ $model->id ] = $model;
            }
            Cache::forever($key,$data);
        }
        return Cache::get($key);
    }
}
