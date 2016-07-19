<?php

namespace Uphold\Tests\Functional;

use Uphold\UpholdClient;

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
        $client = new UpholdClient(array('sandbox' => true));

        $this->client = $client;
    }
}
