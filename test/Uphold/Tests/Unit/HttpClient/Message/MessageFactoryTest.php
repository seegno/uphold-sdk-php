<?php

namespace Uphold\Tests\Unit\HttpClient\Message;

use Seegno\TestBundle\TestCase\BaseTestCase;

/**
 * MessageFactoryTest.
 */
class MessageFactoryTest extends BaseTestCase
{
    /**
     * @test
     */
    public function shouldReturnAResponseWithGivenStatusCode()
    {
        $messageFactory = $this->getMessageFactoryMock();
        $response = $messageFactory->createResponse(204);

        $this->assertEquals(204, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function shouldReturnAResponseWithGivenHeaders()
    {
        $headers = array('waldo' => 'fred');
        $expectedHeaders = array('waldo' => array('fred'));

        $messageFactory = $this->getMessageFactoryMock();
        $response = $messageFactory->createResponse(204, $headers);

        $this->assertEquals($expectedHeaders, $response->getHeaders());
    }

    /**
     * @test
     */
    public function shouldReturnAResponseWithBodyAsNewStreamIfGivenBodyIsNull()
    {
        $messageFactory = $this->getMessageFactoryMock();
        $response = $messageFactory->createResponse(204);

        $this->assertEquals(null, $response->getBody());
    }

    /**
     * @test
     */
    public function shouldReturnAResponseWithBodyAsNewStreamIfGivenBodyIsNotNull()
    {
        $messageFactory = $this->getMessageFactoryMock();
        $response = $messageFactory->createResponse(204, array(), 'foobar');

        $this->assertInstanceOf('GuzzleHttp\Stream\Stream', $response->getBody());
        $this->assertEquals('foobar', $response->getBody()->getContents());
    }

    /**
     * @test
     */
    public function shouldReturnAResponseWithGivenOptions()
    {
        $options = array('protocol_version' => 'foobar');

        $messageFactory = $this->getMessageFactoryMock();
        $response = $messageFactory->createResponse(204, array(), null, $options);

        $this->assertEquals('foobar', $response->getProtocolVersion());
    }

    /**
     * Get message factory mock.
     *
     * @return MessageFactory.
     */
    protected function getMessageFactoryMock()
    {
        return $this
            ->getMockBuilder('Uphold\HttpClient\Message\MessageFactory')
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock()
        ;
    }
}
