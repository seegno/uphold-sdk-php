<?php

namespace Uphold\HttpClient\Message;

use Uphold\HttpClient\Message\Response;
use GuzzleHttp\Message\MessageFactory as BaseMessageFactory;
use GuzzleHttp\Stream\Stream;

/**
 * HTTP request factory used to create Request and Response objects.
 */
class MessageFactory extends BaseMessageFactory
{
    /**
     * Create new response.
     *
     * @param int $statusCode Response status code.
     * @param array $headers Response headers.
     * @param mixed $body Response body.
     * @param array $options Options.
     *
     * @return Response
     */
    public function createResponse($statusCode, array $headers = array(), $body = null, array $options = array())
    {
        if (null !== $body) {
            $body = Stream::factory($body);
        }

        return new Response($statusCode, $headers, $body, $options);
    }
}
