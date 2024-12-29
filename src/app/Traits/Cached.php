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
        $class = \get_called_class();
        $key =  "{$class}-" . __FUNCTION__ . "-{$id}";
        if(!Cache::has($key)){
            Cache::forever($key, $class::find($id));
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
        $enabled_only = false
    ): array
    {
        $class = \get_called_class();
        $table = (new $class())->getTable();
        $key   = "{$table}-" . 
            __FUNCTION__ . 
            "-{$order_by}-{$order_direction}" . 
            (!is_null($enabled_only) ? "-enabled" : '');
        if(!Cache::has($key)){
            $data = [];
            $query = $class::query();
            if($enabled_only){
                $query->where('enabled',1);
            }
            $query = $query
                ->orderBy($order_by,$order_direction)->get() ;
            foreach($query as $model){
                $data[ $model->id ] = $model;
            }
            Cache::forever($key, $data);
        }
        return Cache::get($key);
    }
}
