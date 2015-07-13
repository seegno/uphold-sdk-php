<?php

namespace Bitreserve\Tests\Unit\HttpClient\Message;

/**
 * ResponseTest.
 */
class ResponseTest extends \PHPUnit_Framework_TestCase
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

        $this->assertEquals($data, $response->getContent());
    }

    /**
     * @test
     */
    public function shouldReturnParsedContentRangeHeader()
    {
        $response = $this->getResponseMock();
        $response->expects($this->any())
            ->method('getHeader')
            ->with('Content-Range')
            ->will($this->returnValue('items 0-19/200'));

        $contentRange = $response->getContentRange();

        $this->assertEquals('0', $contentRange['start']);
        $this->assertEquals('19', $contentRange['end']);
        $this->assertEquals('200', $contentRange['count']);
    }

    /**
     * @test
     */
    public function shouldReturnNullIfContentRangeHeaderIsEmpty()
    {
        $response = $this->getResponseMock();
        $response->expects($this->any())
            ->method('getHeader')
            ->with('Content-Range')
            ->will($this->returnValue(''));

        $contentRange = $response->getContentRange();

        $this->assertEquals(NULL, $contentRange);
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

        $this->assertEquals(true, $response->isClientError());
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

        $this->assertEquals(false, $response->isClientError());
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

        $this->assertEquals(true, $response->isServerError());
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

        $this->assertEquals(false, $response->isServerError());
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

        $this->assertEquals($data, $response->getApiRateLimit());
    }

    /**
     * Get response mock.
     *
     * @return Response.
     */
    protected function getResponseMock()
    {
        return $this->getMockBuilder('Bitreserve\HttpClient\Message\Response')
            ->disableOriginalConstructor()
            ->setMethods(array('getBody', 'getStatusCode', 'getHeader'))
            ->getMock();
    }
}
