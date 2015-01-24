<?php

namespace Bitreserve\HttpClient;

use Bitreserve\Exception\ErrorException;
use Bitreserve\Exception\RuntimeException;
use Bitreserve\HttpClient\Handler\ErrorHandler;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Http client used to perform requests on Bitreserve API.
 */
class HttpClient implements HttpClientInterface
{
    /**
     * @var $client
     */
    private $client;

    /**
     * @var $headers
     */
    protected $headers = array();

    /**
     * @var $lastRequest
     */
    private $lastRequest;

    /**
     * @var $lastResponse
     */
    private $lastResponse;

    /**
     * @var $options
     */
    protected $options = array();

    /**
     * Constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $this->options = array_merge($this->options, $options);
        $this->client = new GuzzleClient($this->options);
    }

    /**
     * @return Request
     */
    public function getLastRequest()
    {
        return $this->lastRequest;
    }

    /**
     * @return Response
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    /**
     * {@inheritDoc}
     */
    public function getOption($name)
    {
        return $this->options[$name];
    }

    /**
     * {@inheritDoc}
     */
    public function setHeaders(array $headers)
    {
        $this->headers = array_merge($this->headers, $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function setOption($name, $value)
    {
        $this->options[$name] = $value;
    }

    /**
     * Adds new event listener.
     *
     * @param string    $eventName Event listener.
     * @param array     $listener  Event .
     */
    public function addListener($eventName, $listener)
    {
        $this->client->getEmitter()->on($eventName, $listener);
    }

    /**
     * Add new event subscriber.
     *
     * @param EventSubscriberInterface $subscriber Subscriber object
     */
    public function addSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->client->getEmitter()->attach($subscriber);
    }

    /**
     * {@inheritDoc}
     */
    public function get($path, array $parameters = array(), array $headers = array())
    {
        return $this->request($path, null, 'GET', $headers, array('query' => $parameters));
    }

    /**
     * {@inheritDoc}
     */
    public function post($path, $body = null, array $headers = array())
    {
        return $this->request($path, $body, 'POST', $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function patch($path, $body = null, array $headers = array())
    {
        return $this->request($path, $body, 'PATCH', $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function delete($path, $body = null, array $headers = array())
    {
        return $this->request($path, $body, 'DELETE', $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function put($path, $body, array $headers = array())
    {
        return $this->request($path, $body, 'PUT', $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function request($path, $body, $httpMethod = 'GET', array $headers = array(), array $options = array())
    {
        if (!empty($this->options['api_version'])) {
            $path = sprintf('%s%s', $this->options['api_version'], $path);
        }

        if (!empty($this->options['debug'])) {
            $options['debug'] = $this->options['debug'];
        }

        if (count($headers) > 0) {
            $options['headers'] = $headers;
        }

        $options['body'] = $body;

        $request = $this->client->createRequest($httpMethod, $path, $options);

        try {
            $response = $this->client->send($request);
        } catch(RequestException $e) {
            ErrorHandler::onRequestException($e);
        } catch (\LogicException $e) {
            throw new ErrorException($e->getMessage(), $e->getCode());
        } catch (\RuntimeException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

        $this->lastRequest = $request;
        $this->lastResponse = $response;

        return $response;
    }
}
