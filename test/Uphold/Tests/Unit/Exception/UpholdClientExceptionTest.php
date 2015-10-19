<?php

namespace Uphold\Tests\Unit\Exception;

use Uphold\Exception\UpholdClientException;
use Seegno\TestBundle\TestCase\BaseTestCase;

/**
 * UpholdClientExceptionTest.
 */
class UpholdClientExceptionTest extends BaseTestCase
{
    /**
     * @test
     */
    public function shouldReturnInstanceOfUpholdClientException()
    {
        $exception = new UpholdClientException('foobar');

        $this->assertInstanceOf('Uphold\Exception\UpholdClientException', $exception);
    }

    /**
     * @test
     */
    public function shouldReturnMessage()
    {
        $exception = new UpholdClientException('foobar');

        $this->assertEquals('foobar', $exception->getMessage());
    }

    /**
     * @test
     */
    public function shouldReturnError()
    {
        $exception = new UpholdClientException('foobar', 'Error');

        $this->assertEquals('Error', $exception->getError());
    }

    /**
     * @test
     */
    public function shouldReturnHttpCode()
    {
        $exception = new UpholdClientException('foobar', NULL, 500);

        $this->assertEquals(500, $exception->getHttpCode());
    }

    /**
     * @test
     */
    public function shouldReturnResponse()
    {
        $exception = new UpholdClientException('foobar', NULL, NULL, 'Response');

        $this->assertEquals('Response', $exception->getResponse());
    }

    /**
     * @test
     */
    public function shouldReturnRequest()
    {
        $exception = new UpholdClientException('foobar', NULL, NULL, NULL, 'Request');

        $this->assertEquals('Request', $exception->getRequest());
    }
}
