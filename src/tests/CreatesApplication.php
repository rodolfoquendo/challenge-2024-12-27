<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;

trait CreatesApplication
{
    protected ?Application $test_app = null;
    /**
     * Creates the application.]
     * @return Application 
     */
    public function createApplication(): Application
    {
        if(is_null($this->test_app)){
            $this->test_app = require __DIR__.'/../bootstrap/app.php';
            $this->test_app->make(Kernel::class)->bootstrap();
        }
        return $this->test_app;
    }
}
