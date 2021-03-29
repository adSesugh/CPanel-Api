<?php

namespace Keensoen\CPanelApi\Tests;

use Orchestra\Testbench\TestCase;
use Keensoen\CPanelApi\CPanelApiServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [CPanelApiServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
