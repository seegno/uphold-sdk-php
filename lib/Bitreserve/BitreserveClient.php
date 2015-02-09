<?php

namespace Bitreserve;

use Bitreserve\Exception\RuntimeException;
use Bitreserve\HttpClient\HttpClient;
use Bitreserve\HttpClient\HttpClientInterface;
use Bitreserve\HttpClient\Message\ResponseMediator;
use Bitreserve\Model\Reserve;
use Bitreserve\Model\Ticker;
use Bitreserve\Model\Token;
use Bitreserve\Model\Transaction;

/**
* Bitreserve API client.
*/
class BitreserveClient
{
    /**
     * Guzzle instance used to communicate with Bitreserve.
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
        'base_url' => 'https://api.bitreserve.org/',
        'debug' => false,
        'timeout' => 10,
        'user_agent' => 'bitreserve-sdk-php {version} (https://github.com/seegno/bitreserve-sdk-php)',
        'version' => '1.1.0',
    );

    /**
     * Current Token object.
     *
     * @var Token
     */
    private $token;

    /**
     * Constructor.
     *
     * @param string|null $bearer Authorization Token.
     */
    public function __construct($bearer = null)
    {
        $this->options = array_merge($this->options, array('bearer' => $bearer));

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
     * Retrieve all available currencies.
     *
     * @return array
     */
    public function getCurrencies()
    {
        $tickers = $this->getTicker();

        return array_reduce($tickers, function($currencies, $ticker) {
            if (in_array($ticker->getCurrency(), $currencies)) {
                return $currencies;
            }

            $currencies[] = $ticker->getCurrency();

            return $currencies;
        }, array());
    }

    /**
     * Retrieve all exchanges rates for all currency pairs.
     *
     * @return array
     */
    public function getTicker()
    {
        $data = $this->get('/ticker');

        return array_map(function($ticker) {
            return new Ticker($this, $ticker);
        }, $data);
    }

    /**
     * Retrieve all exchanges rates relative to a given currency.
     *
     * @param string $currency The filter currency.
     *
     * @return array
     */
    public function getTickerByCurrency($currency)
    {
        $data = $this->get(sprintf('/ticker/%s', rawurlencode($currency)));

        return array_map(function($ticker) {
            return new Ticker($this, $ticker);
        }, $data);
    }

    /**
     * Get the current token or create a new one.
     *
     * @return Token
     */
    public function getToken()
    {
        if ($this->token) {
            return $this->token;
        }

        $this->token = new Token($this);

        return $this->token;
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
     * Get current user.
     *
     * @return User
     */
    public function getUser()
    {
        return $this->getToken()->getUser();
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
            'X-Bitreserve-OTP' => $otp,
        ));

        return $this->post('/me/tokens',
            array('description' => $description),
            $headers
        );
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
        $response = $this->getHttpClient()->get(
            $this->buildPath($path),
            $parameters,
            array_merge($this->getDefaultHeaders(), $requestHeaders)
        );

        return ResponseMediator::getContent($response);
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
        $response = $this->getHttpClient()->post(
            $this->buildPath($path),
            $this->createJsonBody($parameters),
            array_merge($this->getDefaultHeaders(), $requestHeaders)
        );

        return ResponseMediator::getContent($response);
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
        $response = $this->getHttpClient()->patch(
            $this->buildPath($path),
            $this->createJsonBody($parameters),
            array_merge($this->getDefaultHeaders(), $requestHeaders)
        );

        return ResponseMediator::getContent($response);
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
        $response = $this->getHttpClient()->put(
            $this->buildPath($path),
            $this->createJsonBody($parameters),
            array_merge($this->getDefaultHeaders(), $requestHeaders)
        );

        return ResponseMediator::getContent($response);
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
        $response = $this->getHttpClient()->delete(
            $this->buildPath($path),
            $this->createJsonBody($parameters),
            array_merge($this->getDefaultHeaders(), $requestHeaders)
        );

        return ResponseMediator::getContent($response);
    }

    /**
     * Build the API path that includes the API version.
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
