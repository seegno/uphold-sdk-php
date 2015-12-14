<?php

namespace Uphold\Tests\Unit\HttpClient;

use Seegno\TestBundle\TestCase\BaseTestCase;
use Uphold\Factory\UpholdClientFactory;

/**
 * UpholdClientFactoryTest.
 */
class UpholdClientFactoryTest extends BaseTestCase
{
    /**
     * @test
     */
    public function shouldReturnInstanceOfUpholdClient()
    {
        $factory = new UpholdClientFactory();

        $this->assertInstanceOf('Uphold\UpholdClient', $factory->create());
    }

    /**
     * @test
     */
    public function shouldReturUpholdClientWithGivenOptions()
    {
        $factory = new UpholdClientFactory();
        $client = $factory->create(array('foo' => 'bar'));

        $this->assertEquals('bar', $client->getOption('foo'));
    }
}
