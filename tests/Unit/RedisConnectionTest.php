<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;

class RedisConnectionTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_it_can_connect_to_redis()
    {
        Redis::set('name', 'Redis');
        Session::put('redis', 'Connected');

        $this->assertEquals(Redis::get('name'), 'Redis');
        $this->assertEquals(Session::get('redis'), 'Connected');
    }
}
