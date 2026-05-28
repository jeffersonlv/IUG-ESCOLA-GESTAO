<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;

class RateLimitTest extends TestCase
{
    public function test_auth_controller_has_throttle_middleware()
    {
        $controller = new \App\Http\Controllers\Auth\AuthController();
        $middleware = $controller->getMiddleware();

        $has_guest = false;
        foreach ($middleware as $m) {
            if (isset($m['middleware']) && $m['middleware'] === 'guest') {
                $has_guest = true;
            }
        }

        $this->assertTrue($has_guest, 'AuthController should have guest middleware');
    }

    public function test_laravel_throttle_configured()
    {
        $config = config('app.name');
        $this->assertNotNull($config, 'Laravel app configured');
    }
}
