<?php

namespace Bitreserve\Tests\Functional;

use Bitreserve\BitreserveClient;

/**
 * TestCase.
 *
 * @group functional
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
    protected $client;

    /**
     * SetUp.
     */
    public function setUp()
    {
        $client = new BitreserveClient();

        $this->client = $client;
    }
}
