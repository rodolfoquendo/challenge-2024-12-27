<?php

namespace App\Traits;

use App\Exceptions\AuthInvalidCredentials;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;

trait Http 
{  
    /**
     * success
     *
     * @param  mixed $data
     * @return string
     */
    public function success($payload): string
    {
        return $this->response($payload, Response::HTTP_OK);
    }

    /**
     * 
     * @param  Throwable $error
     * @param  int       $httpCode The http error code
     * @return string
     */
    public function error(\Throwable $error, int $httpCode = Response::HTTP_INTERNAL_SERVER_ERROR): string
    {
        $payload = [
            'error' => $error->getMessage(),
        ];
        if(env('APP_ENV') != 'prod'){
            $payload['stack'] = $error->getTrace();
        }
        $httpCode = $error instanceof AuthInvalidCredentials ? Response::HTTP_I_AM_A_TEAPOT : $httpCode;
        return $this->response($payload, $httpCode);
    }

    /**
     * 
     * @param  mixed $payload
     * @param  int   $httpCode
     *
     * @return string           The response
     *
     * @author Rodolfo Oquendo <rodolfoquendo@gmail.com>
     * @copyright 2024 Afluenta
     */
    public function response($payload, int $httpCode = Response::HTTP_OK): string
    {
        if($payload instanceof Model){
            $payload = $payload->toArray();
            if(isset($payload['password'])){
                unset($payload['password']);
            }
        }
        return response()->api($payload, $httpCode);


    }
}