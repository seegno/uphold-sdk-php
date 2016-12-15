<?php

namespace Uphold\Tests\Unit;

use Seegno\TestBundle\TestCase\BaseTestCase;
use Uphold\Model\Rate;
use Uphold\Model\User;
use Uphold\UpholdClient;

/**
 * UpholdClientTest.
 */
class UpholdClientTest extends BaseTestCase
{
    /**
     * @test
     */
    public function shouldReturnInstanceOfUpholdClient()
    {
        $client = new UpholdClient();

        $this->assertInstanceOf('Uphold\UpholdClient', $client);
    }

    /**
     * @test
     */
    public function shouldReturnInstanceOfHttpClient()
    {
        $client = new UpholdClient();

        $this->assertInstanceOf('Uphold\HttpClient\HttpClient', $client->getHttpClient());
    }

    /**
     * @test
     */
    public function shouldReturnInstanceOfUpholdClientFactory()
    {
        $client = new UpholdClient();

        $this->assertInstanceOf('Uphold\Factory\UpholdClientFactory', $client->getFactory());
    }

    /**
     * @test
     */
    public function shouldReturnOptionWhenPassingInConstructor()
    {
        $client = new UpholdClient(array('foo' => 'bar'));

        $this->assertEquals('bar', $client->getOption('foo'));
    }

    /**
     * @test
     */
    public function shouldReturnNullIfOptionIsNotDefined()
    {
        $client = new UpholdClient();

        $this->assertEquals(null, $client->getOption('foobar'));
    }

    /**
     * @test
     */
    public function shouldReturnAllOptions()
    {
        $options = array(
            'foo' => 'bar',
            'waldo' => 'fred',
        );

        $client = new UpholdClient();

        $this->setReflectionProperty($client, 'options', $options);

        $this->assertEquals($options, $client->getOptions());
    }

    /**
     * @test
     */
    public function shouldReturnRates()
    {
        $data = array(array(
            'ask' => $this->getFaker()->randomFloat,
            'bid' => $this->getFaker()->randomFloat,
            'currency' => $this->getFaker()->currencyCode,
            'pair' => sprintf('%s%s', $this->getFaker()->currencyCode, $this->getFaker()->currencyCode),
        ), array(
            'ask' => $this->getFaker()->randomFloat,
            'bid' => $this->getFaker()->randomFloat,
            'currency' => $this->getFaker()->currencyCode,
            'pair' => sprintf('%s%s', $this->getFaker()->currencyCode, $this->getFaker()->currencyCode),
        ));

        $response = $this->getResponseMock($data);

        $client = $this
            ->getMockBuilder('Uphold\UpholdClient')
            ->setMethods(array('get'))
            ->getMock()
        ;

        $client
            ->expects($this->once())
            ->method('get')
            ->with('/ticker')
            ->will($this->returnValue($response))
        ;

        $rates = $client->getRates();

        $this->assertCount(count($data), $rates);

        foreach ($rates as $rate) {
            $this->assertInstanceOf('Uphold\Model\Rate', $rate);
        }
    }

    /**
     * @test
     */
    public function shouldReturnRatesByCurrency()
    {
        $expectedCurrency = $this->getFaker()->currencyCode;

        $data = array(array(
            'ask' => $this->getFaker()->randomFloat,
            'bid' => $this->getFaker()->randomFloat,
            'currency' => $expectedCurrency,
            'pair' => sprintf('%s%s', $expectedCurrency, $this->getFaker()->currencyCode),
        ), array(
            'ask' => $this->getFaker()->randomFloat,
            'bid' => $this->getFaker()->randomFloat,
            'currency' => $this->getFaker()->currencyCode,
            'pair' => sprintf('%s%s', $expectedCurrency, $this->getFaker()->currencyCode),
        ));

        $response = $this->getResponseMock($data);

        $client = $this
            ->getMockBuilder('Uphold\UpholdClient')
            ->setMethods(array('get'))
            ->getMock()
        ;

        $client
            ->expects($this->once())
            ->method('get')
            ->with(sprintf('/ticker/%s', $expectedCurrency))
            ->will($this->returnValue($response))
        ;

        $rates = $client->getRatesByCurrency($expectedCurrency);

        $this->assertCount(count($data), $rates);

        foreach ($rates as $rate) {
            $this->assertInstanceOf('Uphold\Model\Rate', $rate);

            $this->assertRegExp(sprintf('/%s/', $expectedCurrency), $rate->getPair());
        }
    }

    /**
     * @test
     */
    public function shouldReturnCurrencies()
    {
        $expectedCurrencies = array(
            $this->getFaker()->currencyCode,
            $this->getFaker()->currencyCode,
        );

        $client = $this
            ->getMockBuilder('Uphold\UpholdClient')
            ->setMethods(array('getRates'))
            ->getMock()
        ;

        $rate1 = new Rate($client, array(
            'ask' => $this->getFaker()->randomFloat,
            'bid' => $this->getFaker()->randomFloat,
            'currency' => $expectedCurrencies[0],
            'pair' => sprintf('%s%s', $expectedCurrencies[0], $this->getFaker()->currencyCode),
        ));

        $rate2 = new Rate($client, array(
            'ask' => $this->getFaker()->randomFloat,
            'bid' => $this->getFaker()->randomFloat,
            'currency' => $expectedCurrencies[1],
            'pair' => sprintf('%s%s', $expectedCurrencies[1], $this->getFaker()->currencyCode),
        ));

        $rate3 = new Rate($client, array(
            'ask' => $this->getFaker()->randomFloat,
            'bid' => $this->getFaker()->randomFloat,
            'currency' => $expectedCurrencies[1],
            'pair' => sprintf('%s%s', $expectedCurrencies[1], $this->getFaker()->currencyCode),
        ));

        $client
            ->expects($this->once())
            ->method('getRates')
            ->willReturn(array($rate1, $rate2, $rate3))
        ;

        $currencies = $client->getCurrencies();

        $this->assertCount(count($expectedCurrencies), $currencies);

        foreach ($currencies as $currency) {
            $this->assertContains($currency, $expectedCurrencies);
        }
    }

    /**
     * @test
     */
    public function shouldReturnTransactions()
    {
        $data = array(array(
            'id' => 'a97bb994-6e24-4a89-b653-e0a6d0bcf634',
            'foo' => 'bar',
        ), array(
            'id' => '63dc7ccb-0e57-400d-8ea7-7d903753801c',
            'foo' => 'bar',
        ));

        $response = $this->getResponseMock($data);

        $client = $this->getUpholdClientMock();

        $client
            ->expects($this->once())
            ->method('get')
            ->with('/reserve/transactions')
            ->will($this->returnValue($response))
        ;

        $pager = $client->getTransactions();

        $this->assertInstanceOf('Uphold\Paginator\Paginator', $pager);

        $transactions = $pager->getNext();

        foreach ($transactions as $transaction) {
            $this->assertInstanceOf('Uphold\Model\Transaction', $transaction);
        }
    }

    /**
     * @test
     */
    public function shouldReturnOneTransaction()
    {
        $expectedTransactionId = 'a97bb994-6e24-4a89-b653-e0a6d0bcf634';

        $data = array(
            'id' => 'a97bb994-6e24-4a89-b653-e0a6d0bcf634',
            'foo' => 'bar',
        );

        $response = $this->getResponseMock($data);

        $client = $this->getUpholdClientMock();

        $client
            ->expects($this->once())
            ->method('get')
            ->with(sprintf('/reserve/transactions/%s', $expectedTransactionId))
            ->will($this->returnValue($response))
        ;

        $transaction = $client->getTransactionById($expectedTransactionId);

        $this->assertInstanceOf('Uphold\Model\Transaction', $transaction);
        $this->assertEquals($expectedTransactionId, $transaction->getId());
    }

    /**
     * @test
     */
    public function shouldReturnReserve()
    {
        $client = new UpholdClient();

        $this->assertInstanceOf('Uphold\Model\Reserve', $client->getReserve());
    }

    /**
     * @test
     */
    public function shouldReturnPropertyReserveIfIsDefined()
    {
        $client = new UpholdClient();

        $this->setReflectionProperty($client, 'reserve', 'foobar');

        $this->assertEquals('foobar', $client->getReserve());
    }

    /**
     * @test
     */
    public function shouldReturnUser()
    {
        $data = array('username' => $this->getFaker()->userName);

        $response = $this->getResponseMock($data);
        $userClient = $this->getUpholdClientMock();

        $userClient
            ->expects($this->once())
            ->method('get')
            ->with('/me')
            ->will($this->returnValue($response))
        ;

        $factory = $this
            ->getMockBuilder('Uphold\Factory\UpholdClientFactory')
            ->disableOriginalConstructor()
            ->setMethods(array('create'))
            ->getMock()
        ;

        $factory
            ->expects($this->once())
            ->method('create')
            ->with($this->callback(function($options) {
                return !empty($options['bearer']) && 'foobar' === $options['bearer'];
            }))
            ->will($this->returnValue($userClient))
        ;

        $client = $this->getUpholdClientMock(array('getFactory'));

        $client
            ->expects($this->once())
            ->method('getFactory')
            ->will($this->returnValue($factory))
        ;

        $user = $client->getUser('foobar');

        $this->assertInstanceOf('Uphold\Model\User', $user);
        $this->assertEquals($data['username'], $user->getUsername());
    }

    /**
     * @test
     * @expectedException Uphold\Exception\AuthenticationRequiredException
     * @expectedExceptionMessage Missing `client_id` option
     */
    public function shouldThrowAuthenticationRequiredExceptionOnAuthorizeUserWhenClientIdIsMissing()
    {
        $client = $this->getUpholdClientMock();

        $client
            ->expects($this->any())
            ->method('getOption')
            ->withConsecutive(array('client_id'), array('client_secret'))
            ->will($this->returnValue(null))
        ;

        $client->authorizeUser('foobar');
    }

    /**
     * @test
     * @expectedException Uphold\Exception\AuthenticationRequiredException
     * @expectedExceptionMessage Missing `client_secret` option
     */
    public function shouldThrowAuthenticationRequiredExceptionOnAuthorizeUserWhenClientSecretIsMissing()
    {
        $client = $this->getUpholdClientMock();

        $client
            ->expects($this->any())
            ->method('getOption')
            ->withConsecutive(array('client_id'), array('client_secret'))
            ->will($this->onConsecutiveCalls('qux', null))
        ;

        $client->authorizeUser('foobar');
    }

    /**
     * @test
     */
    public function shouldReturnAuthorizedUserWhenResponseReturnsBearerToken()
    {
        $clientId = 'qux';
        $clientSecret = 'waldo';
        $code = 'foobar';

        $expectedHeaders = array(
            'Accept' => 'application/x-www-form-urlencoded',
            'Authorization' => sprintf('Basic %s', base64_encode(sprintf('%s:%s', $clientId, $clientSecret))),
            'Content-Type' => 'application/x-www-form-urlencoded',
            'foo' => 'bar',
        );

        $expectedParameters = http_build_query(array(
            'code' => $code,
            'grant_type' => 'authorization_code',
        ));

        $response = $this->getResponseMock(array('access_token' => 'xyzzy'));

        $httpClient = $this->getHttpClientMock();

        $httpClient
            ->expects($this->once())
            ->method('post')
            ->with('/oauth2/token', $expectedParameters, $expectedHeaders)
            ->will($this->returnValue($response))
        ;

        $client = $this->getUpholdClientMock(array('getDefaultHeaders', 'getHttpClient', 'getUser'));

        $client
            ->expects($this->any())
            ->method('getOption')
            ->withConsecutive(array('client_id'), array('client_secret'))
            ->will($this->onConsecutiveCalls($clientId, $clientSecret))
        ;

        $client
            ->expects($this->any())
            ->method('getDefaultHeaders')
            ->will($this->returnValue(array('foo' => 'bar')))
        ;

        $client
            ->expects($this->once())
            ->method('getHttpClient')
            ->will($this->returnValue($httpClient))
        ;

        $client
            ->expects($this->once())
            ->method('getUser')
            ->with('xyzzy')
            ->will($this->returnValue('fred'))
        ;

        $this->assertEquals('fred', $client->authorizeUser($code));
    }

    /**
     * @test
     */
    public function shouldCreateToken()
    {
        $description = $this->getFaker()->sentence;
        $login = $this->getFaker()->userName;
        $otp = null;
        $password = $this->getFaker()->password;

        $headers = array(
            'Authorization' => sprintf('Basic %s', base64_encode(sprintf('%s:%s', $login, $password))),
            'OTP-Token' => $otp,
        );

        $data = array('foo' => 'bar');

        $response = $this->getResponseMock($data);

        $client = $this
            ->getMockBuilder('Uphold\UpholdClient')
            ->setMethods(array('getDefaultHeaders', 'post'))
            ->getMock()
        ;

        $client
            ->expects($this->any())
            ->method('getDefaultHeaders')
            ->will($this->returnValue(array()))
        ;

        $client
            ->expects($this->once())
            ->method('post')
            ->with('/me/tokens', array('description' => $description), $headers)
            ->will($this->returnValue($response))
        ;

        $this->assertEquals($data, $client->createToken($login, $password, $description));
    }

    /**
     * @test
     */
    public function shouldCreateTokenWithOTP()
    {
        $description = $this->getFaker()->sentence;
        $login = $this->getFaker()->userName;
        $otp = $this->getFaker()->randomNumber(6);
        $password = $this->getFaker()->password;

        $headers = array(
            'Authorization' => sprintf('Basic %s', base64_encode(sprintf('%s:%s', $login, $password))),
            'OTP-Token' => $otp,
        );

        $data = array('foo' => 'bar');

        $response = $this->getResponseMock($data);

        $client = $this
            ->getMockBuilder('Uphold\UpholdClient')
            ->setMethods(array('getDefaultHeaders', 'post'))
            ->getMock()
        ;

        $client
            ->expects($this->any())
            ->method('getDefaultHeaders')
            ->will($this->returnValue(array()))
        ;

        $client
            ->expects($this->once())
            ->method('post')
            ->with('/me/tokens', array('description' => $description), $headers)
            ->will($this->returnValue($response))
        ;

        $this->assertEquals($data, $client->createToken($login, $password, $description, $otp));
    }

    /**
     * @test
     * @dataProvider getDefaultRequestHttpMethods
     */
    public function shouldSendRequestToClient($httpMethod, $encodedBody)
    {
        $apiVersion = 'v0';
        $options = array('option1' => 'optValue1');
        $params = array('param1' => 'paramValue1');
        $path = '/path';

        $expectedArray = array('value');

        $response = $this->getResponseMock($expectedArray);

        $client = $this
            ->getMockBuilder('Uphold\UpholdClient')
            ->setMethods(null)
            ->getMock()
        ;

        $headers = array(
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'User-Agent' => str_replace('{version}', sprintf('v%s', $client->getOption('version')), $client->getOption('user_agent')),
        );

        $body = $encodedBody ? json_encode($params) : $params;

        $httpClient = $this->getHttpClientMock();

        $httpClient
            ->expects($this->once())
            ->method($httpMethod)
            ->with(sprintf('%s%s', $apiVersion, $path), $body, array_merge($options, $headers))
            ->will($this->returnValue($response))
        ;

        $client->setHttpClient($httpClient);
        $client->setOption('api_version', $apiVersion);

        $response = $client->$httpMethod('/path', $params, $options);

        $this->assertEquals($expectedArray, $response->getContent());
    }

    /**
     * @test
     * @dataProvider getDefaultRequestHttpMethods
     */
    public function shouldSendRequestToClientWithAuthorizationToken($httpMethod, $encodedBody)
    {
        $apiVersion = 'v0';
        $bearer = 'foobar';
        $options = array('option1' => 'optValue1');
        $params = array('param1' => 'paramValue1');
        $path = '/path';

        $expectedArray = array('value');

        $response = $this->getResponseMock($expectedArray);

        $client = $this
            ->getMockBuilder('Uphold\UpholdClient')
            ->setMethods(null)
            ->getMock()
        ;

        $headers = array(
            'Accept' => 'application/json',
            'Authorization' => sprintf('Bearer %s', $bearer),
            'Content-Type' => 'application/json',
            'User-Agent' => str_replace('{version}', sprintf('v%s', $client->getOption('version')), $client->getOption('user_agent')),
        );

        $body = $encodedBody ? json_encode($params) : $params;

        $httpClient = $this->getHttpClientMock();

        $httpClient
            ->expects($this->once())
            ->method($httpMethod)
            ->with(sprintf('%s%s', $apiVersion, $path), $body, array_merge($options, $headers))
            ->will($this->returnValue($response))
        ;

        $client->setHttpClient($httpClient);
        $client->setOption('api_version', $apiVersion);
        $client->setOption('bearer', $bearer);

        $response = $client->$httpMethod('/path', $params, $options);

        $this->assertEquals($expectedArray, $response->getContent());
    }

    /**
     * @test
     * @dataProvider getDefaultRequestHttpMethods
     */
    public function shouldSendRequestWithoutApiVersionToClient($httpMethod, $encodedBody)
    {
        $options = array('option1' => 'optValue1');
        $params = array('param1' => 'paramValue1');
        $path = '/path';

        $expectedArray = array('value');

        $response = $this->getResponseMock($expectedArray);

        $client = $this
            ->getMockBuilder('Uphold\UpholdClient')
            ->setMethods(null)
            ->getMock()
        ;

        $headers = array(
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'User-Agent' => str_replace('{version}', sprintf('v%s', $client->getOption('version')), $client->getOption('user_agent')),
        );

        $body = $encodedBody ? json_encode($params) : $params;

        $httpClient = $this->getHttpClientMock();

        $httpClient
            ->expects($this->once())
            ->method($httpMethod)
            ->with($path, $body, array_merge($options, $headers))
            ->will($this->returnValue($response))
        ;

        $client->setHttpClient($httpClient);
        $client->setOption('api_version', null);

        $response = $client->$httpMethod('/path', $params, $options);

        $this->assertEquals($expectedArray, $response->getContent());
    }

    /**
     * @test
     */
    public function shouldUseBodyAsAnObjectWhenParametersAreEmpty()
    {
        $body = json_encode(array(), JSON_FORCE_OBJECT);

        $httpClient = $this->getHttpClientMock();

        $httpClient
            ->expects($this->once())
            ->method('post')
            ->with('foobar', $body)
        ;

        $client = $this
            ->getMockBuilder('Uphold\UpholdClient')
            ->setMethods(array('buildPath', 'getHttpClient', 'getDefaultHeaders'))
            ->getMock()
        ;

        $client->method('buildPath')->willReturn('foobar');
        $client->method('getDefaultHeaders')->willReturn(array());
        $client->method('getHttpClient')->willReturn($httpClient);

        $client->post('/path', array());
    }

    /**
     * Get default request http methods provider.
     *
     * @return array
     */
    public function getDefaultRequestHttpMethods()
    {
        return array(
            array('delete', true),
            array('get', false),
            array('patch', true),
            array('post', true),
            array('put', true),
        );
    }

    /**
     * Get UpholdClient mock.
     *
     * @return UpholdClient
     */
    protected function getUpholdClientMock(array $methods = array())
    {
        $methods = array_merge(
            array('get', 'post', 'patch', 'put', 'delete', 'request', 'setOption', 'getOption', 'setHeaders'),
            $methods
        );

        return $this
            ->getMockBuilder('Uphold\UpholdClient')
            ->setMethods($methods)
            ->getMock()
        ;
    }

    /**
     * Get HttpClient mock.
     *
     * @return HttpClient
     */
    protected function getHttpClientMock()
    {
        $methods = array('get', 'post', 'patch', 'put', 'delete', 'request', 'setOption', 'setHeaders');

        return $this
            ->getMockBuilder('Uphold\HttpClient\HttpClientInterface')
            ->setMethods($methods)
            ->getMock()
        ;
    }

    /**
     * Get Response mock.
     *
     * @param string $content Response content.
     *
     * @return Response
     */
    protected function getResponseMock($content = null)
    {
        $response = $this
            ->getMockBuilder('Uphold\HttpClient\Message\Response')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        if (null === $content) {
            return $response;
        }

        $response
            ->expects($this->any())
            ->method('getContent')
            ->will($this->returnValue($content))
        ;

        return $response;
    }
}
