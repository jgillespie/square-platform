<?php

namespace App\Modules\Core\Tests\Config;

use App\Modules\Core\Tests\TestCase;

class CoreTest extends TestCase
{
    /** @test */
    public function it_has_backend_routes_prefix()
    {
        $this->assertNotNull(config('core.backend_routes_prefix'));
    }

    /** @test */
    public function it_has_frontend_routes_prefix()
    {
        $this->assertNotNull(config('core.frontend_routes_prefix'));
    }

    /** @test */
    public function it_has_user_registration()
    {
        $this->assertNotNull(config('core.user_registration'));
    }

    /** @test */
    public function it_has_auth_image()
    {
        $this->assertNotNull(config('core.auth_image'));
    }

    /** @test */
    public function it_has_logo()
    {
        $this->assertNotNull(config('core.logo'));
    }

    /** @test */
    public function it_has_copyright_link()
    {
        $this->assertNotNull(config('core.copyright_link'));
    }

    /** @test */
    public function it_has_copyright_name()
    {
        $this->assertNotNull(config('core.copyright_name'));
    }
}
