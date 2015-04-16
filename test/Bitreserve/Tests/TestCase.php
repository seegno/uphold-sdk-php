<?php

namespace Bitreserve\Tests;

use Faker\Factory as FakerFactory;

/**
 * TestCase.
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
    protected $faker;

    /**
     * SetUp.
     */
    public function setUp()
    {
        $this->faker = FakerFactory::create();
    }
}
