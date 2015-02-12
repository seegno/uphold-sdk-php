<?php

namespace Bitreserve\Tests\Model;

/**
 * TestCase.
 */
abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    abstract protected function getModelClass();

    protected function getModelMock()
    {
        $httpClient = $this->getMock('GuzzleHttp\Client', array('send'));
        $httpClient->expects($this->any())
            ->method('send');

        $httpMock = $this->getMock('Bitreserve\HttpClient\HttpClient', array(), array(array(), $httpClient));

        $client = new \Bitreserve\BitreserveClient(null, $httpMock);
        $client->setHttpClient($httpMock);

        return $this->getMockBuilder($this->getModelClass())
            ->setMethods(array('get', 'post', 'patch', 'delete', 'put', 'head'))
            ->setConstructorArgs(array($client))
            ->getMock();
    }

    protected function getBitreserveClientMock(array $methods = array())
    {
        $methods = array_merge(
            array('get', 'post', 'patch', 'put', 'delete', 'request', 'setOption', 'setHeaders', 'getOption'),
            $methods
        );

        return $this->getMock('Bitreserve\BitreserveClient', $methods);
    }

    protected function getHttpClientMock(array $methods = array())
    {
        $methods = array_merge(
            array('get', 'post', 'patch', 'put', 'delete', 'request', 'setOption', 'setHeaders', 'getOption'),
            $methods
        );

        return $this->getMock('Bitreserve\HttpClient\HttpClientInterface', $methods);
    }

    protected function getResponseMock($content = null)
    {
        $response = $this->getMockBuilder('Bitreserve\HttpClient\Message\Response')
            ->disableOriginalConstructor()
            ->getMock();

        if (null === $content) {
            return $response;
        }

        $response->expects($this->any())
            ->method('getContent')
            ->will($this->returnValue($content));

        return $response;
    }
}
