<?php

namespace Tests\Unit;
use \App\Services\FileService;

class InitTest extends \Tests\TestCase
{

    /**
     * A basic test example.
     */
    public function test__invoke(): void
    {
        $this->createApplication();
        $this->assertTrue(true);
    }

}
