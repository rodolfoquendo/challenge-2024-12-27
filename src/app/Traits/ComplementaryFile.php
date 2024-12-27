<?php 
namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait ComplementaryFile{

    const MAX_UPLOAD_TRIES_S3 = 3;

    public function addFile($path, $s3_upload_tries = 0){
        $path = \realpath(__DIR__ . "/../../storage/app/public/{$path}");
        if(!\file_exists($path)){
            throw new \Exception("file not uploaded: {$path}");
        }

        $ext         = pathinfo($path, PATHINFO_EXTENSION);
        if($this->ext != $ext){
            $this->ext = $ext;
            $this->save();
        }
        $s3_location = "/complementary_data/{$this->table}/{$this->id}.{$ext}";
        $s3 = Storage::disk('s3');
        if($s3->put($s3_location, file_get_contents($path,true),'public')){
            if ($s3->exists($s3_location)) {
                unlink($path);
                \gc_collect_cycles();
            }
        }elseif($s3_upload_tries < self::MAX_UPLOAD_TRIES_S3 ){
            $s3_upload_tries++;
            return $this->addFile($path, $s3_upload_tries);
        }else{
            throw new \Exception("file not uploaded to s3: {$path}");
        }
    }

    public function getFile($url = false){
        $s3_location = "complementary_data/{$this->table}/{$this->id}.{$this->ext}";
        $s3 = Storage::disk('s3');
        if (!$s3->exists($s3_location)) {
            throw new \Exception("file do not exists in s3: {$s3_location}");
        }
        if($url){
            $bucket = env('AWS_BUCKET');
            return "https://{$bucket}.s3.amazonaws.com/{$s3_location}";
        }else{
            return $s3->get($s3_location);
        }
    }
}
