<?php

namespace Uphold;

use Uphold\Exception\AuthenticationRequiredException;
use Uphold\Factory\UpholdClientFactory;
use Uphold\HttpClient\HttpClient;
use Uphold\HttpClient\HttpClientInterface;
use Uphold\Model\Rate;
use Uphold\Model\Reserve;
use Uphold\Model\Token;
use Uphold\Model\Transaction;
use Uphold\Model\User;

/**
* Uphold API client.
*/
class UpholdClient
{
    /**
     * Uphold API urls.
     */
    const UPHOLD_API_URL = 'https://api.uphold.com';
    const UPHOLD_SANDBOX_API_URL = 'https://api-sandbox.uphold.com';

    /**
     * Guzzle instance used to communicate with Uphold.
     *
     * @var HttpClient
     */
    private $httpClient;

    /**
     * Current Reserve object.
     *
     * @var Reserve
     */
    private $reserve;

    /**
     * @var array
     */
    private $options = array(
        'api_version' => 'v0',
        'debug' => false,
        'sandbox' => false,
        'timeout' => 10,
        'user_agent' => 'uphold-sdk-php {version} (https://github.com/seegno/uphold-sdk-php)',
        'version' => '5.0.1',
    );

    /**
     * Constructor.
     *
     * @param Array $options UpholdClient options.
     */
    public function __construct(array $options = array())
    {
        if (!isset($options['base_url'])) {
            $options['base_url'] = isset($options['sandbox']) && $options['sandbox'] ? self::UPHOLD_SANDBOX_API_URL : self::UPHOLD_API_URL;
        }

        $this->options = array_merge($this->options, $options);

        $this->setHttpClient(new HttpClient($this->options));
    }

    /**
     * Get Http client.
     *
     * @return HttpClientInterface $httpClient
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * Get UpholdClient factory.
     *
     * @return UpholdClientFactory
     */
    public function getFactory()
    {
        return new UpholdClientFactory();
    }

    /**
     * Get client option.
     *
     * @param string $name Option name.
     *
     * @return mixed
     */
    public function getOption($name)
    {
        if (!isset($this->options[$name])) {
            return null;
        }

        return $this->options[$name];
    }

    /**
     * Sets client option.
     *
     * @param string $name Option name.
     * @param mixed $value Option value.
     */
    public function setOption($name, $value)
    {
        $this->options[$name] = $value;

        return $this;
    }

    /**
     * Get all client options.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param HttpClientInterface $httpClient
     */
    public function setHttpClient(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Retrieve all available currencies.
     *
     * @return array
     */
    public function getCurrencies()
    {
        $rates = $this->getRates();

        return array_reduce($rates, function($currencies, $rate) {
            if (in_array($rate->getCurrency(), $currencies)) {
                return $currencies;
            }

            $currencies[] = $rate->getCurrency();

            return $currencies;
        }, array());
    }

    /**
     * Retrieve all exchanges rates for all currency pairs.
     *
     * @return array
     */
    public function getRates()
    {
        $response = $this->get('/ticker');

        return array_map(function($rate) {
            return new Rate($this,($rate));
        }, $response->getContent());
    }

    /**
     * Retrieve all exchanges rates relative to a given currency.
     *
     * @param string $currency The filter currency.
     *
     * @return array
     */
    public function getRatesByCurrency($currency)
    {
        $response = $this->get(sprintf('/ticker/%s', rawurlencode($currency)));

        return array_map(function($rate) {
            return new Rate($this, $rate);
        }, $response->getContent());
    }

    /**
     * Return the public view of any transaction.
     *
     * @param string $id The transaction id.
     *
     * @return Transaction
     *
     * @deprecated Method deprecated in Release 1.2.0
     */
    public function getTransactionById($id)
    {
        return $this->getReserve()->getTransactionById($id);
    }

    /**
     * Return the public view of all transactions from the beginning of time.
     *
     * @return array
     *
     * @deprecated Method deprecated in Release 1.2.0
     */
    public function getTransactions()
    {
        return $this->getReserve()->getTransactions();
    }

    /**
     * Get a reserve object or create a new one.
     *
     * @return Reserve
     */
    public function getReserve()
    {
        if ($this->reserve) {
            return $this->reserve;
        }

        $this->reserve = new Reserve($this);

        return $this->reserve;
    }

    /**
     * Get user.
     *
     * @return User
     */
    public function getUser($bearerToken)
    {
        $options = array_merge($this->options, array('bearer' => $bearerToken));

        $client = $this->getFactory()->create($options);

        $response = $client->get('/me');

        return new User($client, $response->getContent());
    }

    /**
     * Create a new Personal Access Token (PAT).
     *
     * @param string $login Login email or username.
     * @param string $password Password.
     * @param string $description PAT description.
     * @param string $otp Verification code
     *
     * @return array
     */
    public function createToken($login, $password, $description, $otp = null)
    {
        $headers = array_merge($this->getDefaultHeaders(), array(
            'Authorization' => sprintf('Basic %s', base64_encode(sprintf('%s:%s', $login, $password))),
            'OTP-Token' => $otp,
        ));

        $response = $this->post('/me/tokens',
            array('description' => $description),
            $headers
        );

        return $response->getContent();
    }

    /**
     * Authorize user via Uphold Connect.
     *
     * @param string $code The code parameter that is passed via the Uphold Connect callback url.
     *
     * @return User
     */
    public function authorizeUser($code)
    {
        $clientId = $this->getOption('client_id');
        $clientSecret = $this->getOption('client_secret');

        if (!$clientId) {
            throw new AuthenticationRequiredException('Missing `client_id` option');
        }

        if (!$clientSecret) {
            throw new AuthenticationRequiredException('Missing `client_secret` option');
        }

        $headers = array(
            'Accept' => 'application/x-www-form-urlencoded',
            'Authorization' => sprintf('Basic %s', base64_encode(sprintf('%s:%s', $clientId, $clientSecret))),
            'Content-Type' => 'application/x-www-form-urlencoded',
        );

        $parameters = http_build_query(array(
            'code' => $code,
            'grant_type' => 'authorization_code',
        ));

        $response = $this->getHttpClient()->post(
            '/oauth2/token',
            $parameters,
            array_merge($this->getDefaultHeaders(), $headers)
        );

        $content = $response->getContent();
        $bearerToken = isset($content['access_token']) ? $content['access_token'] : null;

        return $this->getUser($bearerToken);
    }

    /**
     * Send a GET request with query parameters.
     *
     * @param string $path Request path.
     * @param array $parameters GET parameters.
     * @param array $requestHeaders Request Headers.
     *
     * @return \GuzzleHttp\EntityBodyInterface|mixed|string
     */
    public function get($path, array $parameters = array(), $requestHeaders = array())
    {
        return $this->getHttpClient()->get(
            $this->buildPath($path),
            $parameters,
            array_merge($this->getDefaultHeaders(), $requestHeaders)
        );
    }

    /**
     * Send a POST request with JSON-encoded parameters.
     *
     * @param string $path Request path.
     * @param array $parameters POST parameters to be JSON encoded.
     * @param array $requestHeaders Request headers.
     *
     * @return \GuzzleHttp\EntityBodyInterface|mixed|string
     */
    public function post($path, array $parameters = array(), $requestHeaders = array())
    {
        return $this->getHttpClient()->post(
            $this->buildPath($path),
            $this->createJsonBody($parameters),
            array_merge($this->getDefaultHeaders(), $requestHeaders)
        );
    }

    /**
     * Send a PATCH request with JSON-encoded parameters.
     *
     * @param string $path Request path.
     * @param array $parameters POST parameters to be JSON encoded.
     * @param array $requestHeaders Request headers.
     *
     * @return \GuzzleHttp\EntityBodyInterface|mixed|string
     */
    public function patch($path, array $parameters = array(), $requestHeaders = array())
    {
        return $this->getHttpClient()->patch(
            $this->buildPath($path),
            $this->createJsonBody($parameters),
            array_merge($this->getDefaultHeaders(), $requestHeaders)
        );
    }

    /**
     * Send a PUT request with JSON-encoded parameters.
     *
     * @param string $path Request path.
     * @param array $parameters POST parameters to be JSON encoded.
     * @param array $requestHeaders Request headers.
     *
     * @return \GuzzleHttp\EntityBodyInterface|mixed|string
     */
    public function put($path, array $parameters = array(), $requestHeaders = array())
    {
        return $this->getHttpClient()->put(
            $this->buildPath($path),
            $this->createJsonBody($parameters),
            array_merge($this->getDefaultHeaders(), $requestHeaders)
        );
    }

    /**
     * Send a DELETE request with JSON-encoded parameters.
     *
     * @param string $path Request path.
     * @param array $parameters POST parameters to be JSON encoded.
     * @param array $requestHeaders Request headers.
     *
     * @return \GuzzleHttp\EntityBodyInterface|mixed|string
     */
    public function delete($path, array $parameters = array(), $requestHeaders = array())
    {
        return $this->getHttpClient()->delete(
            $this->buildPath($path),
            $this->createJsonBody($parameters),
            array_merge($this->getDefaultHeaders(), $requestHeaders)
        );
    }

    /**
     * Build the API path that includes the API version.
     *
     * @param string $path The path to append to the base URL.
     *
     * @return string
     */
    protected function buildPath($path)
    {
        if (empty($this->options['api_version'])) {
            return $path;
        }

        return sprintf('%s%s', $this->options['api_version'], $path);
    }

    /**
     * Create a JSON encoded version of an array of parameters.
     *
     * @param array $parameters Request parameters
     *
     * @return null|string
     */
    protected function createJsonBody(array $parameters)
    {
        $options = 0;

        if (empty($parameters)) {
          $options = JSON_FORCE_OBJECT;
        }

        return json_encode($parameters, $options);
    }

    /**
     * Create the API default headers that are mandatory.
     *
     * @return array
     */
    protected function getDefaultHeaders()
    {
        $headers = array(
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'User-Agent' => str_replace('{version}', sprintf('v%s', $this->getOption('version')), $this->getOption('user_agent')),
        );

        if (null !== $this->getOption('bearer') && '' !== $this->getOption('bearer')) {
            $headers['Authorization'] = sprintf('Bearer %s', $this->getOption('bearer'));
        }

        return $headers;
    }
}
