<?php

namespace Tests\App\Models;

use App\Models\Gender;
use Illuminate\Http\Response;

class GenderTest extends \Tests\TestCase
{

    public function test__FindCached(): void
    {
        $this->createApplication();
        $this->assertTrue(Gender::findCached(Gender::M) instanceof Gender);
        $this->assertTrue(Gender::findCached(Gender::M)->id == Gender::find(Gender::M)->id);
    }

    public function test__GetCached(): void
    {
        $this->createApplication();
        $cached = Gender::getCached();
        $live   = Gender::get();
        $this->assertTrue(count($cached) == count($live));
        $this->assertTrue($cached[Gender::M]->id == $live[1]->id);
    }

    public function test__structure(){
        $this->createApplication();
        $model = Gender::findCached(Gender::M);
        $this->assertTrue(isset($model->id));
        $this->assertTrue(isset($model->cod));
        $this->assertTrue(isset($model->title));
        $this->assertTrue(count($model->participants) > 0);
    }

}