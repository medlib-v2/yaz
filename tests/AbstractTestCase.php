<?php

namespace Medlib\Testing;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

abstract class AbstractTestCase extends TestCase
{
    /**
     * @var int
     */
    protected $testNowTimestamp;

    public function setUp()
    {
        parent::setUp();
        Carbon::setTestNow($now = Carbon::now());
        $this->testNowTimestamp = $now->getTimestamp();
    }
    public function tearDown()
    {
        Carbon::setTestNow();
        parent::tearDown();
    }
}