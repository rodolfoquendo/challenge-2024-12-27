<?php

namespace Tests\App\Models;

use App\Models\Plan;

class PlanTest extends \Tests\TestCase
{

    public function test__FindCached(): void
    {
        $this->createApplication();
        $cached = Plan::findCached(Plan::UNLIMITED);
        $live = Plan::findCached(Plan::UNLIMITED);
        $this->assertTrue($cached instanceof Plan);
        $this->assertTrue($cached->id == $live->id);
        $this->assertTrue(count($cached->users) > 0);
    }

    public function test__GetCached(): void
    {
        $this->createApplication();
        $cached = Plan::getCached();
        $live   = Plan::get();
        $this->assertTrue(count($cached) == count($live));
        $this->assertTrue($cached[Plan::UNLIMITED]->id == $live[0]->id);
    }

    public function test__structure(){
        $this->createApplication();
        $model = Plan::findCached(Plan::FREE);
        $this->assertTrue(isset($model->id));
        $this->assertTrue(isset($model->cod));
        $this->assertTrue(isset($model->title));
        $this->assertTrue(isset($model->enabled));
        $this->assertTrue(isset($model->price_monthly));
        $this->assertTrue(isset($model->price_yearly));
        $this->assertTrue(isset($model->tournaments));
        $this->assertTrue(isset($model->participants));
        $this->assertTrue(isset($model->created_at));
        $this->assertTrue(isset($model->updated_at));
        $this->assertTrue(is_null($model->description));
        $this->assertTrue(is_null($model->deleted_at));
        $this->assertTrue($model->isEnabled());
        $model->disable();
        $this->assertFalse($model->isEnabled());
        $this->assertTrue($model->isDisabled());
        $model->enable();
        $this->assertTrue($model->isEnabled());
        $this->assertTrue(count($model->users) == 0);
    }

    public function test__GetCached__options(){
        $this->createApplication();
        $plans = Plan::getCached(enabled_only: true);
        $this->assertTrue(count($plans) == 3);

    }

}