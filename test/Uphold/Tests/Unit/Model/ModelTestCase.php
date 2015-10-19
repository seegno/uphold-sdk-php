<?php

namespace Uphold\Tests\Unit\Model;

use Seegno\TestBundle\TestCase\BaseTestCase;

/**
 * ModelTestCase.
 */
abstract class ModelTestCase extends BaseTestCase
{
    /**
     * Get Model class.
     *
     * @return string
     */
    abstract protected function getModelClass();

    /**
     * Get Model mock.
     *
     * @return mixed
     */
    protected function getModelMock()
    {
        $httpClient = $this->getMock('GuzzleHttp\Client', array('send'));
        $httpClient->expects($this->any())
            ->method('send');

        $httpMock = $this->getMock('Uphold\HttpClient\HttpClient', array(), array(array(), $httpClient));

        $client = new \Uphold\UpholdClient(null, $httpMock);
        $client->setHttpClient($httpMock);

        return $this->getMockBuilder($this->getModelClass())
            ->setMethods(array('get', 'post', 'patch', 'delete', 'put', 'head'))
            ->setConstructorArgs(array($client))
            ->getMock();
    }

    /**
     * Get UpholdClient mock.
     *
     * @param array $methods Mocked methods.
     *
     * @return UpholdClient
     */
    protected function getUpholdClientMock(array $methods = array())
    {
        $methods = array_merge(
            array('get', 'post', 'patch', 'put', 'delete', 'request', 'setOption', 'setHeaders', 'getOption'),
            $methods
        );

        return $this->getMock('Uphold\UpholdClient', $methods);
    }

    /**
     * Get HttpClient mock.
     *
     * @param array $methods Mocked methods.
     *
     * @return HttpClient
     */
    protected function getHttpClientMock(array $methods = array())
    {
        $methods = array_merge(
            array('get', 'post', 'patch', 'put', 'delete', 'request', 'setOption', 'setHeaders', 'getOption'),
            $methods
        );

        return $this->getMock('Uphold\HttpClient\HttpClientInterface', $methods);
    }

    /**
     * Get Response mock.
     *
     * @param mixed $content Response content.
     *
     * @return Response
     */
    protected function getResponseMock($content = null)
    {
        $response = $this->getMockBuilder('Uphold\HttpClient\Message\Response')
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
