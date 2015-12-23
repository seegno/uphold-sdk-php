<?php

namespace Uphold\HttpClient;

use GuzzleHttp\Message\Response;

/**
 * Performs requests on Uphold API.
 */
interface HttpClientInterface
{
    /**
     * Send a GET request.
     */
    public function get($path, array $parameters = array(), array $headers = array());

    /**
     * Send a POST request.
     */
    public function post($path, $body = null, array $headers = array());

    /**
     * Send a PATCH request.
     */
    public function patch($path, $body = null, array $headers = array());

    /**
     * Send a PUT request.
     */
    public function put($path, $body, array $headers = array());

    /**
     * Send a DELETE request.
     */
    public function delete($path, $body = null, array $headers = array());

    /**
     * Send a request to the server, receive a response,
     * decode the response and returns an associative array.
     */
    public function request($path, $body, $httpMethod = 'GET', array $headers = array());

    /**
     * Change an option value.
     */
    public function setOption($name, $value);
}
