<?php

namespace Bitreserve\Tests\Functional;

use Bitreserve\BitreserveClient;
use Bitreserve\Exception\ApiLimitExceedException;
use Bitreserve\Exception\RuntimeException;

/**
 * TestCase.
 *
 * @group functional
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
    protected $client;

    public function setUp()
    {
        $client = new BitreserveClient();

        $this->client = $client;
    }
}
