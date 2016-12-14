<?php

namespace Uphold\Tests\Unit\HttpClient\Message;

use Seegno\TestBundle\TestCase\BaseTestCase;

/**
 * ResponseTest.
 */
class ResponseTest extends BaseTestCase
{
    /**
     * @test
     */
    public function shouldReturnBodyIfAnErrorOccursWhenDecodingBody()
    {
        $body = 'foobar';

        $response = $this->getResponseMock(array('getBody'));

        $response
            ->expects($this->any())
            ->method('getBody')
            ->with(true)
            ->will($this->returnValue($body))
        ;

        $this->assertEquals($body, $response->getContent());
    }

    /**
     * @test
     */
    public function shouldReturnResponseContent()
    {
        $data = array('foo' => 'bar');

        $response = $this->getResponseMock(array('getBody'));

        $response
            ->expects($this->any())
            ->method('getBody')
            ->will($this->returnValue(json_encode($data)))
        ;

        $this->assertEquals($data, $response->getContent());
    }

    /**
     * @test
     */
    public function shouldReturnParsedContentRangeHeader()
    {
        $response = $this->getResponseMock(array('getHeader'));

        $response
            ->expects($this->any())
            ->method('getHeader')
            ->with('Content-Range')
            ->will($this->returnValue('items 0-19/200'))
        ;

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
        $response = $this->getResponseMock(array('getHeader'));

        $response
            ->expects($this->any())
            ->method('getHeader')
            ->with('Content-Range')
            ->will($this->returnValue(''))
        ;

        $contentRange = $response->getContentRange();

        $this->assertEquals(NULL, $contentRange);
    }

    /**
     * @test
     */
    public function shouldReturnUnknownErrorIfContentIsNotAnArray()
    {
        $response = $this->getResponseMock(array('getContent'));

        $response
            ->expects($this->any())
            ->method('getContent')
            ->will($this->returnValue('foobar'))
        ;

        $this->assertEquals('unknown_error', $response->getError());
    }

    /**
     * @test
     */
    public function shouldReturnContentErrorIfIsNotEmpty()
    {
        $response = $this->getResponseMock(array('getContent'));

        $response
            ->expects($this->any())
            ->method('getContent')
            ->willReturn(array('error' => 'foobar'))
        ;

        $this->assertEquals('foobar', $response->getError());
    }

    /**
     * @test
     */
    public function shouldReturnContentCodeIfIsNotEmpty()
    {
        $response = $this->getResponseMock(array('getContent'));

        $response
            ->expects($this->any())
            ->method('getContent')
            ->willReturn(array('code' => 'foobar'))
        ;

        $this->assertEquals('foobar', $response->getError());
    }

    /**
     * @test
     */
    public function shouldReturnUnknownErrorIfContentErrorAndCodeIsEmpty()
    {
        $response = $this->getResponseMock(array('getContent'));

        $response
            ->expects($this->any())
            ->method('getContent')
            ->willReturn(array())
        ;

        $this->assertEquals('unknown_error', $response->getError());
    }


    /**
     * @test
     */
    public function shouldReturnContentErrorsIfIsNotEmpty()
    {
        $errors =  array('foo' => 'bar', 'baderous' => array('qux'));

        $response = $this->getResponseMock(array('getContent'));

        $response
            ->expects($this->any())
            ->method('getContent')
            ->willReturn(array('errors' => $errors))
        ;

        $expected = sprintf('Errors: %s', json_encode($errors));

        $this->assertEquals($expected, $response->getErrorDescription());
    }

    /**
     * @test
     */
    public function shouldReturnContentErrorDescriptionIfIsNotEmpty()
    {
        $response = $this->getResponseMock(array('getContent'));

        $response
            ->expects($this->any())
            ->method('getContent')
            ->willReturn(array('error_description' => 'foobar'))
        ;

        $this->assertEquals('foobar', $response->getErrorDescription());
    }

    /**
     * @test
     */
    public function shouldReturnContentMessageIfIsNotEmpty()
    {
        $response = $this->getResponseMock(array('getContent'));

        $response
            ->expects($this->any())
            ->method('getContent')
            ->willReturn(array('message' => 'foobar'))
        ;

        $this->assertEquals('foobar', $response->getErrorDescription());
    }

    /**
     * @test
     */
    public function shouldReturnUnknownErrorDescriptionIfContentErrorAndCodeIsEmpty()
    {
        $response = $this->getResponseMock(array('getContent'));

        $response
            ->expects($this->any())
            ->method('getContent')
            ->willReturn(array())
        ;

        $this->assertEquals('An unknown error occurred', $response->getErrorDescription());
    }

    /**
     * @test
     */
    public function shouldCheckIfIsClientErrorAndReturnTrueWhenStatusCodeIs400()
    {
        $response = $this->getResponseMock(array('getStatusCode'));

        $response
            ->expects($this->any())
            ->method('getStatusCode')
            ->will($this->returnValue(400))
        ;

        $this->assertEquals(true, $response->isClientError());
    }

    /**
     * @test
     */
    public function shouldCheckIfIsClientErrorAndReturnFalseWhenStatusCodeIs300()
    {
        $response = $this->getResponseMock(array('getStatusCode'));

        $response
            ->expects($this->any())
            ->method('getStatusCode')
            ->will($this->returnValue(300))
        ;

        $this->assertEquals(false, $response->isClientError());
    }

    /**
     * @test
     */
    public function shouldCheckIfIsServerErrorAndReturnTrueWhenStatusCodeIs500()
    {
        $response = $this->getResponseMock(array('getStatusCode'));

        $response
            ->expects($this->any())
            ->method('getStatusCode')
            ->will($this->returnValue(500))
        ;

        $this->assertEquals(true, $response->isServerError());
    }

    /**
     * @test
     */
    public function shouldCheckIfIsServerErrorAndReturnFalseWhenStatusCodeIs300()
    {
        $response = $this->getResponseMock(array('getStatusCode'));

        $response
            ->expects($this->any())
            ->method('getStatusCode')
            ->will($this->returnValue(300))
        ;

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

        $response = $this->getResponseMock(array('getHeader', 'getStatusCode'));

        $response
            ->expects($this->any())
            ->method('getStatusCode')
            ->will($this->returnValue(429))
        ;

        $response
            ->expects($this->exactly(3))
            ->method('getHeader')
            ->withConsecutive(
                array('Rate-Limit-Total'),
                array('Rate-Limit-Remaining'),
                array('Rate-Limit-Reset')
            )
            ->will($this->onConsecutiveCalls($data['limit'], $data['remaining'], $data['reset']))
        ;

        $this->assertEquals($data, $response->getApiRateLimit());
    }

    /**
     * Get response mock.
     *
     * @return Response.
     */
    protected function getResponseMock($methods = null)
    {
        return $this
            ->getMockBuilder('Uphold\HttpClient\Message\Response')
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock()
        ;
    }
}
