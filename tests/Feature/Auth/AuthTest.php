<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AuthTest extends TestCase
{

    public function test_middleware_active_exists()
    {
        $kernel = app(\App\Http\Kernel::class);
        $this->assertArrayHasKey('active', $kernel->getRouteMiddleware());
    }

    public function test_auth_controller_has_login_method()
    {
        $controller = new \App\Http\Controllers\Auth\AuthController();
        $this->assertTrue(method_exists($controller, 'login'));
        $this->assertTrue(method_exists($controller, 'logout'));
        $this->assertTrue(method_exists($controller, 'showLogin'));
    }

    public function test_check_user_active_middleware_exists()
    {
        $this->assertTrue(class_exists(\App\Http\Middleware\CheckUserActive::class));
    }
}
