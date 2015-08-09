<?php

namespace Bitreserve\Tests\Unit;

use Bitreserve\BitreserveClient;
use Bitreserve\Model\User;
use Seegno\TestBundle\TestCase\BaseTestCase;

/**
 * BitreserveClientTest.
 */
class BitreserveClientTest extends BaseTestCase
{
    /**
     * @test
     */
    public function shouldReturnInstanceOfBitreserveClient()
    {
        $client = new BitreserveClient();

        $this->assertInstanceOf('Bitreserve\BitreserveClient', $client);
    }

    /**
     * @test
     */
    public function shouldReturnInstanceOfHttpClient()
    {
        $client = new BitreserveClient();

        $this->assertInstanceOf('Bitreserve\HttpClient\HttpClient', $client->getHttpClient());
    }

    /**
     * @test
     */
    public function shouldReturnOptionWhenPassingInConstructor()
    {
        $client = new BitreserveClient(array('foo' => 'bar'));

        $this->assertEquals('bar', $client->getOption('foo'));
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
            ->getMockBuilder('Bitreserve\BitreserveClient')
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
            $this->assertInstanceOf('Bitreserve\Model\Rate', $rate);
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
            ->getMockBuilder('Bitreserve\BitreserveClient')
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
            $this->assertInstanceOf('Bitreserve\Model\Rate', $rate);

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

        $data = array(array(
            'ask' => $this->getFaker()->randomFloat,
            'bid' => $this->getFaker()->randomFloat,
            'currency' => $expectedCurrencies[0],
            'pair' => sprintf('%s%s', $expectedCurrencies[0], $this->getFaker()->currencyCode),
        ), array(
            'ask' => $this->getFaker()->randomFloat,
            'bid' => $this->getFaker()->randomFloat,
            'currency' => $expectedCurrencies[1],
            'pair' => sprintf('%s%s', $expectedCurrencies[1], $this->getFaker()->currencyCode),
        ));

        $response = $this->getResponseMock($data);

        $client = $this
            ->getMockBuilder('Bitreserve\BitreserveClient')
            ->setMethods(array('get'))
            ->getMock()
        ;

        $client
            ->expects($this->once())
            ->method('get')
            ->with('/ticker')
            ->will($this->returnValue($response))
        ;

        $currencies = $client->getCurrencies();

        $this->assertCount(count($data), $currencies);

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

        $client = $this->getBitreserveClientMock();

        $client
            ->expects($this->once())
            ->method('get')
            ->with('/reserve/transactions')
            ->will($this->returnValue($response))
        ;

        $pager = $client->getTransactions();

        $this->assertInstanceOf('Bitreserve\Paginator\Paginator', $pager);

        $transactions = $pager->getNext();

        foreach ($transactions as $transaction) {
            $this->assertInstanceOf('Bitreserve\Model\Transaction', $transaction);
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

        $client = $this->getBitreserveClientMock();

        $client
            ->expects($this->once())
            ->method('get')
            ->with(sprintf('/reserve/transactions/%s', $expectedTransactionId))
            ->will($this->returnValue($response))
        ;

        $transaction = $client->getTransactionById($expectedTransactionId);

        $this->assertInstanceOf('Bitreserve\Model\Transaction', $transaction);
        $this->assertEquals($expectedTransactionId, $transaction->getId());
    }

    /**
     * @test
     */
    public function shouldReturnReserve()
    {
        $client = new BitreserveClient();

        $this->assertInstanceOf('Bitreserve\Model\Reserve', $client->getReserve());
    }

    /**
     * @test
     */
    public function shouldReturnUser()
    {
        $data = array('username' => $this->getFaker()->userName);

        $response = $this->getResponseMock($data);
        $userClient = $this->getBitreserveClientMock();

        $userClient
            ->expects($this->once())
            ->method('get')
            ->with('/me')
            ->will($this->returnValue($response))
        ;

        $factory = $this
            ->getMockBuilder('Bitreserve\Factory\BitreserveClientFactory')
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

        $client = $this->getBitreserveClientMock(array('getFactory'));

        $client
            ->expects($this->once())
            ->method('getFactory')
            ->will($this->returnValue($factory))
        ;

        $user = $client->getUser('foobar');

        $this->assertInstanceOf('Bitreserve\Model\User', $user);
        $this->assertEquals($data['username'], $user->getUsername());
    }

    /**
     * @test
     * @expectedException Bitreserve\Exception\AuthenticationRequiredException
     * @expectedExceptionMessage Missing `client_id` option
     */
    public function shouldThrowAuthenticationRequiredExceptionOnAuthorizeUserWhenClientIdIsMissing()
    {
        $client = $this->getBitreserveClientMock();

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
     * @expectedException Bitreserve\Exception\AuthenticationRequiredException
     * @expectedExceptionMessage Missing `client_secret` option
     */
    public function shouldThrowAuthenticationRequiredExceptionOnAuthorizeUserWhenClientSecretIsMissing()
    {
        $client = $this->getBitreserveClientMock();

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

        $response = $this->getResponseMock('xyzzy');

        $httpClient = $this->getHttpClientMock();

        $httpClient
            ->expects($this->once())
            ->method('post')
            ->with('/oauth2/token', $expectedParameters, $expectedHeaders)
            ->will($this->returnValue($response))
        ;

        $client = $this->getBitreserveClientMock(array('getDefaultHeaders', 'getHttpClient', 'getUser'));

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
            'X-Bitreserve-OTP' => $otp,
        );

        $data = array('foo' => 'bar');

        $response = $this->getResponseMock($data);

        $client = $this
            ->getMockBuilder('Bitreserve\BitreserveClient')
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
            'X-Bitreserve-OTP' => $otp,
        );

        $data = array('foo' => 'bar');

        $response = $this->getResponseMock($data);

        $client = $this
            ->getMockBuilder('Bitreserve\BitreserveClient')
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
        $defaultOptions = array('defaultOption' => 'defaultValue');
        $options = array('option1' => 'optValue1');
        $params = array('param1' => 'paramValue1');
        $path = '/path';

        $expectedArray = array('value');

        $response = $this->getResponseMock($expectedArray);

        $client = $this
            ->getMockBuilder('Bitreserve\BitreserveClient')
            ->setMethods(array('createJsonBody', 'getDefaultHeaders'))
            ->getMock()
        ;

        $client
            ->expects($this->any())
            ->method('createJsonBody')
            ->will($this->returnValue(json_encode($params)))
        ;

        $client
            ->expects($this->once())
            ->method('getDefaultHeaders')
            ->will($this->returnValue($defaultOptions))
        ;

        $body = $encodedBody ? json_encode($params) : $params;

        $httpClient = $this->getHttpClientMock();

        $httpClient
            ->expects($this->once())
            ->method($httpMethod)
            ->with(sprintf('%s%s', $apiVersion, $path), $body, array_merge($options, $defaultOptions))
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
    public function shouldSendRequestWithoutApiVersionToClient($httpMethod, $encodedBody)
    {
        $defaultOptions = array('defaultOption' => 'defaultValue');
        $options = array('option1' => 'optValue1');
        $params = array('param1' => 'paramValue1');
        $path = '/path';

        $expectedArray = array('value');

        $response = $this->getResponseMock($expectedArray);

        $client = $this
            ->getMockBuilder('Bitreserve\BitreserveClient')
            ->setMethods(array('createJsonBody', 'getDefaultHeaders'))
            ->getMock()
        ;

        $client
            ->expects($this->any())
            ->method('createJsonBody')
            ->will($this->returnValue(json_encode($params)))
        ;

        $client
            ->expects($this->once())
            ->method('getDefaultHeaders')
            ->will($this->returnValue($defaultOptions))
        ;

        $body = $encodedBody ? json_encode($params) : $params;

        $httpClient = $this->getHttpClientMock();

        $httpClient
            ->expects($this->once())
            ->method($httpMethod)
            ->with($path, $body, array_merge($options, $defaultOptions))
            ->will($this->returnValue($response))
        ;

        $client->setHttpClient($httpClient);
        $client->setOption('api_version', null);

        $response = $client->$httpMethod('/path', $params, $options);

        $this->assertEquals($expectedArray, $response->getContent());
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
     * Get BitreserveClient mock.
     *
     * @return BitreserveClient
     */
    protected function getBitreserveClientMock(array $methods = array())
    {
        $methods = array_merge(
            array('get', 'post', 'patch', 'put', 'delete', 'request', 'setOption', 'getOption', 'setHeaders'),
            $methods
        );

        return $this
            ->getMockBuilder('Bitreserve\BitreserveClient')
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
            ->getMockBuilder('Bitreserve\HttpClient\HttpClientInterface')
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
            ->getMockBuilder('Bitreserve\HttpClient\Message\Response')
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
