<?php

namespace {{NAMESPACE}};

use Tests\TestCase;
use {{SERVICE_NAMESPACE}}\{{CLASS}};

class {{TEST_CLASS}} extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $instance = new {{CLASS}}();
        $this->assertNotNull($instance);
    }

    {{METHODS}}
}
