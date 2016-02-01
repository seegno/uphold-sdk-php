<?php

namespace Uphold\Tests\Unit\HttpClient;

use Uphold\UpholdClient;
use Uphold\HttpClient\HttpClient;
use Uphold\HttpClient\Listener\ErrorListener;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Message\Request;
use GuzzleHttp\Message\Response;
use Guzzle\Plugin\Mock\MockPlugin;
use ReflectionProperty;
use Seegno\TestBundle\TestCase\BaseTestCase;

/**
 * HttpClientTest.
 */
class HttpClientTest extends BaseTestCase
{
    /**
     * @test
     */
    public function shouldReturnInstanceOfHttpClient()
    {
        $httpClient = new HttpClient();

        $this->assertInstanceOf('Uphold\HttpClient\HttpClient', $httpClient);
    }

    /**
     * @test
     */
    public function shouldBeAbleToPassOptionsToConstructor()
    {
        $options = array(
            'foo' => 'bar',
            'timeout' => $this->getFaker()->randomDigitNotNull,
        );

        $httpClient = new HttpClient($options);

        $this->assertEquals($options['timeout'], $httpClient->getOption('timeout'));
        $this->assertEquals('bar', $httpClient->getOption('foo'));
    }

    /**
     * @test
     */
    public function shouldBeAbleToSetOption()
    {
        $timeout = $this->getFaker()->randomDigitNotNull;

        $httpClient = new HttpClient();
        $httpClient->setOption('timeout', $timeout);

        $this->assertEquals($timeout, $httpClient->getOption('timeout'));
    }

    /**
     * @test
     */
    public function shouldDoGETRequest()
    {
        $headers = array('c' => 'd');
        $parameters = array('a' => 'b');
        $path = '/some/path';

        $httpClient = $this->getHttpClientMock(array('request'));

        $httpClient
            ->expects($this->once())
            ->method('request')
            ->with($path, null, 'GET', $headers, array('query' => $parameters))
        ;

        $httpClient->get($path, $parameters, $headers);
    }

    /**
     * @test
     */
    public function shouldDoPOSTRequest()
    {
        $body = array('a' => 'b');
        $headers = array('c' => 'd');
        $path = '/some/path';

        $httpClient = $this->getHttpClientMock(array('request'));

        $httpClient
            ->expects($this->once())
            ->method('request')
            ->with($path, $body, 'POST', $headers)
        ;

        $httpClient->post($path, $body, $headers);
    }

    /**
     * @test
     */
    public function shouldDoPOSTRequestWithoutContent()
    {
        $path = '/some/path';

        $httpClient = $this->getHttpClientMock(array('request'));

        $httpClient
            ->expects($this->once())
            ->method('request')
            ->with($path, null, 'POST', $this->isType('array'))
        ;

        $httpClient->post($path);
    }

    /**
     * @test
     */
    public function shouldDoPATCHRequest()
    {
        $body = array('a' => 'b');
        $headers = array('c' => 'd');
        $path = '/some/path';

        $httpClient = $this->getHttpClientMock(array('request'));

        $httpClient
            ->expects($this->once())
            ->method('request')
            ->with($path, $body, 'PATCH', $headers)
        ;

        $httpClient->patch($path, $body, $headers);
    }

    /**
     * @test
     */
    public function shouldDoDELETERequest()
    {
        $body = array('a' => 'b');
        $headers = array('c' => 'd');
        $path = '/some/path';

        $httpClient = $this->getHttpClientMock(array('request'));

        $httpClient
            ->expects($this->once())
            ->method('request')
            ->with($path, $body, 'DELETE', $headers)
        ;

        $httpClient->delete($path, $body, $headers);
    }

    /**
     * @test
     */
    public function shouldDoPUTequest()
    {
        $body = array('a' => 'b');
        $headers = array('c' => 'd');
        $path = '/some/path';

        $httpClient = $this->getHttpClientMock(array('request'));

        $httpClient
            ->expects($this->once())
            ->method('request')
            ->with($path, $body, 'PUT', $headers)
        ;

        $httpClient->put($path, $body, $headers);
    }

    /**
     * @test
     */
    public function shouldDoCustomRequest()
    {
        $body = array('a' => 'b');
        $headers = array('c' => 'd');
        $httpMethod = 'custom';
        $options = array('e' => 'f');
        $path = '/some/path';

        $request = new Request($httpMethod, $path);
        $response = new Response(200, array('foo' => 'bar'));

        $client = $this->getClientMock();

        $client
            ->expects($this->once())
            ->method('createRequest')
            ->with($httpMethod, $path, array_merge($options, array('headers' => $headers, 'body' => $body)))
            ->will($this->returnValue($request))
        ;

        $client
            ->expects($this->once())
            ->method('send')
            ->with($request)
            ->will($this->returnValue($response))
        ;

        $httpClient = $this->getHttpClientMock(array('createRequest'));

        $this->setReflectionProperty($httpClient, 'client', $client);

        $httpClientResponse = $httpClient->request($path, $body, $httpMethod, $headers, $options);

        $this->assertEquals($response, $httpClientResponse);
    }

    /**
     * @test
     */
    public function shouldUseDebugOptionIfIsDefined()
    {
        $body = array('a' => 'b');
        $options = array('body' => $body, 'debug' => 'qux');
        $path = '/some/path';

        $request = new Request('GET', $path);

        $client = $this->getClientMock();

        $client
            ->expects($this->once())
            ->method('createRequest')
            ->with('GET', $path, $options)
            ->will($this->returnValue($request))
        ;

        $httpClient = $this->getHttpClientMock(array('createRequest'));
        $httpClient->setOption('debug', 'qux');

        $this->setReflectionProperty($httpClient, 'client', $client);

        $httpClient->request($path, $body);
    }

    /**
     * @test
     * @expectedException Uphold\Exception\LogicException
     */
    public function shouldThrowLogicExceptionWhenClientSendThrownsLogicException()
    {
        $body = array('a' => 'b');
        $headers = array('c' => 'd');
        $httpMethod = 'custom';
        $options = array('e' => 'f');
        $path = '/some/path';

        $request = new Request($httpMethod, $path);
        $response = new Response(200, array('foo' => 'bar'));

        $httpClient = $this->getHttpClientMock(array('createRequest'));

        $client = $this->getClientMock();

        $client
            ->expects($this->once())
            ->method('createRequest')
            ->with($httpMethod, $path, array_merge($options, array('headers' => $headers, 'body' => $body)))
            ->will($this->returnValue($request))
        ;

        $client
            ->expects($this->once())
            ->method('send')
            ->with($request)
            ->will($this->throwException(new \LogicException('Testing logic exception', 500)))
        ;

        $this->setReflectionProperty($httpClient, 'client', $client);

        $httpClient->request($path, $body, $httpMethod, $headers, $options);
    }

    /**
     * @test
     * @expectedException Uphold\Exception\RuntimeException
     */
    public function shouldThownRuntimeExceptionWhenClientSendThrownsRuntimeException()
    {
        $body = array('a' => 'b');
        $headers = array('c' => 'd');
        $httpMethod = 'custom';
        $options = array('e' => 'f');
        $path = '/some/path';

        $request = new Request($httpMethod, $path);
        $response = new Response(200, array('foo' => 'bar'));

        $client = $this->getClientMock();

        $client
            ->expects($this->once())
            ->method('createRequest')
            ->with($httpMethod, $path, array_merge($options, array('headers' => $headers, 'body' => $body)))
            ->will($this->returnValue($request))
        ;

        $client
            ->expects($this->once())
            ->method('send')
            ->with($request)
            ->will($this->throwException(new \RuntimeException('Testing runtime exception', 500)))
        ;

        $httpClient = $this->getHttpClientMock(array('createRequest'));

        $this->setReflectionProperty($httpClient, 'client', $client);

        $httpClient->request($path, $body, $httpMethod, $headers, $options);
    }

    /**
     * @test
     */
    public function shouldAllowToReturnRawContent()
    {
        $headers = array('c' => 'd');
        $httpMethod = 'get';
        $parameters = array('a = b');
        $path = '/some/path';

        $message = $this->getMock('GuzzleHttp\Message\Response', array(), array(200));

        $message
            ->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue('Just raw context'))
        ;

        $httpClient = new HttpClient();

        $client = $this->getClientMock();

        $client
            ->expects($this->once())
            ->method('createRequest')
            ->will($this->returnValue(new Request($httpMethod, $path)))
        ;

        $client
            ->expects($this->once())
            ->method('send')
            ->will($this->returnValue($message));

        $this->setReflectionProperty($httpClient, 'client', $client);

        $response = $httpClient->get($path, $parameters, $headers);

        $this->assertEquals("Just raw context", $response->getBody());
        $this->assertInstanceOf('GuzzleHttp\Message\MessageInterface', $response);
    }

    /**
     * Get `HttpClient` mock.
     *
     * @return HttpClient
     */
    protected function getHttpClientMock(array $methods = null)
    {
        return $this
            ->getMockBuilder('Uphold\HttpClient\HttpClient')
            ->setMethods($methods)
            ->getMock()
        ;
    }

    /**
     * Get `Client` mock.
     *
     * @return Client
     */
    protected function getClientMock()
    {
        return $this
            ->getMockBuilder('GuzzleHttp\Client')
            ->setMethods(array('send', 'createRequest'))
            ->getMock()
        ;
    }
}
