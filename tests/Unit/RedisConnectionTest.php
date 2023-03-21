<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class RedisConnectionTest extends TestCase
{
    /**
     * Test ping redis command.
     */
    public function test_ping(): void
    {
        $redisConn = Redis::connection();
        $this->assertEquals('PONG', $redisConn->command('ping'));
    }

    /**
     * Test set get redis command.
     */
    public function test_set_get(): void
    {
        $redisConn = Redis::connection();
        $redisConn->command('set', ['entah', 'entah']);
        $commandResult = $redisConn->command('get', ['entah']);
        $this->assertEquals('entah', $commandResult);
    }
}
