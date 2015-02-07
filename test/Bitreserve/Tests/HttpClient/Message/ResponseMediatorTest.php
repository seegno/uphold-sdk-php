<?php

namespace Bitreserve\Tests\HttpClient\Message;

use Bitreserve\HttpClient\Message\ResponseMediator;

class ResponseMediatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldReturnResponseContent()
    {
        $data = array('foo' => 'bar');

        $response = $this->getResponseMock();
        $response->expects($this->any())
            ->method('getBody')
            ->will($this->returnValue(json_encode($data)));

        $this->assertEquals($data, ResponseMediator::getContent($response));
    }

    /**
     * @test
     */
    public function shouldCheckIfIsClientErrorAndReturnTrueWhenStatusCodeIs400()
    {
        $response = $this->getResponseMock();
        $response->expects($this->any())
            ->method('getStatusCode')
            ->will($this->returnValue(400));

        $this->assertEquals(true, ResponseMediator::isClientError($response));
    }

    /**
     * @test
     */
    public function shouldCheckIfIsClientErrorAndReturnFalseWhenStatusCodeIs300()
    {
        $response = $this->getResponseMock();
        $response->expects($this->any())
            ->method('getStatusCode')
            ->will($this->returnValue(300));

        $this->assertEquals(false, ResponseMediator::isClientError($response));
    }

    /**
     * @test
     */
    public function shouldCheckIfIsServerErrorAndReturnTrueWhenStatusCodeIs500()
    {
        $response = $this->getResponseMock();
        $response->expects($this->any())
            ->method('getStatusCode')
            ->will($this->returnValue(500));

        $this->assertEquals(true, ResponseMediator::isServerError($response));
    }

    /**
     * @test
     */
    public function shouldCheckIfIsServerErrorAndReturnFalseWhenStatusCodeIs300()
    {
        $response = $this->getResponseMock();
        $response->expects($this->any())
            ->method('getStatusCode')
            ->will($this->returnValue(300));

        $this->assertEquals(false, ResponseMediator::isServerError($response));
    }

    /**
     * @test
     */
    public function shouldReturnResponseApiRateLimit()
    {
        $data = array(
           'limit' => 300,
           'remaining' => 0,
           'reset' => 1384377793,
        );

        $response = $this->getResponseMock();
        $response->expects($this->any())
            ->method('getStatusCode')
            ->will($this->returnValue(429));
        $response->expects($this->exactly(3))
            ->method('getHeader')
            ->withConsecutive(
                array('X-RateLimit-Limit'),
                array('X-RateLimit-Remaining'),
                array('X-RateLimit-Reset')
            )
            ->will($this->onConsecutiveCalls($data['limit'], $data['remaining'], $data['reset']));

        $this->assertEquals($data, ResponseMediator::getApiRateLimit($response));
    }

    protected function getResponseMock()
    {
        return $this->getMockBuilder('GuzzleHttp\Message\Response')->disableOriginalConstructor()->getMock();
    }
}
