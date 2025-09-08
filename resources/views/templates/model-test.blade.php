<?php

namespace {{NAMESPACE}};

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use {{MODEL_NAMESPACE}}\{{CLASS}};

class {{TEST_CLASS}} extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_model()
    {
        $model = {{CLASS}}::factory()->create();
        $this->assertNotNull($model);
    }

    /** @test */
    public function it_can_update_a_model()
    {
        $model = {{CLASS}}::factory()->create();
        $originalValue = $model->name;
        $model->name = 'Updated Name';
        $model->save();
        
        $this->assertNotEquals($originalValue, $model->fresh()->name);
    }

    /** @test */
    public function it_can_delete_a_model()
    {
        $model = {{CLASS}}::factory()->create();
        $modelId = $model->id;
        
        $model->delete();
        
        $this->assertNull({{CLASS}}::find($modelId));
    }

    {{METHODS}}
}
