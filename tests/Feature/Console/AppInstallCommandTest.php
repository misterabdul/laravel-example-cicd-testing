<?php

namespace Tests\Feature\Console;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AppInstallCommandTest extends TestCase
{
    use RefreshDatabase,
        WithFaker;

    /**
     * Test running app:install artisan command
     */
    public function test_app_install_command(): void
    {
        $output = $this->artisan('app:install');

        $output->assertExitCode(0);
    }
}
