<?php

namespace Bitreserve\HttpClient\Message;

use Bitreserve\Exception\ApiLimitExceedException;
use GuzzleHttp\Message\Response;

class ResponseMediator
{
    /**
     * Gets API rate limits from the response headers.
     *
     * @param  Response $response The response
     * @return array              The complete rate limits headers.
     */
    public static function getApiRateLimit(Response $response)
    {
        return array(
            'limit' => (string) $response->getHeader('X-RateLimit-Limit'),
            'remaining' => (string) $response->getHeader('X-RateLimit-Remaining'),
            'reset' => (string) $response->getHeader('X-RateLimit-Reset'),
        );
    }

    /**
     * Gets the decoded body from the response.
     *
     * @param  Response $response The response
     * @return mixed              Decoded body.
     */
    public static function getContent(Response $response)
    {
        $body = $response->getBody(true);
        $content = json_decode($body, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            return $body;
        }

        return $content;
    }

    /**
     * Checks if the response is a client error.
     *
     * @param  Response $response The response
     * @return boolean            Returns true if the response is a client error.
     */
    public static function isClientError(Response $response)
    {
        return $response->getStatusCode() >= 400 && $response->getStatusCode() < 500;
    }

    /**
     * Checks if the response is a server error.
     *
     * @param  Response $response The response
     * @return boolean            Returns true if the response is a server error.
     */
    public static function isServerError(Response $response)
    {
        return $response->getStatusCode() >= 500 && $response->getStatusCode() < 600;
    }
}
