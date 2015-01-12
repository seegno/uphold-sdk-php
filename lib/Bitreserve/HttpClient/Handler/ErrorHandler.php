<?php

namespace Bitreserve\HttpClient\Handler;

use Bitreserve\Exception\ApiLimitExceedException;
use Bitreserve\Exception\AuthenticationRequiredException;
use Bitreserve\Exception\BadRequestException;
use Bitreserve\Exception\ErrorException;
use Bitreserve\Exception\NotFoundException;
use Bitreserve\Exception\RuntimeException;
use Bitreserve\Exception\ValidationFailedException;
use Bitreserve\HttpClient\Message\ResponseMediator;
use GuzzleHttp\Event\ErrorEvent;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Message\Response;

/**
 * ErrorHandler
 */
class ErrorHandler
{
    /**
     * {@inheritDoc}
     */
    public static function onRequestException(\Exception $e)
    {
        $response = $e->getResponse();

        $isClientError = ResponseMediator::isClientError($response);
        $isServerError = ResponseMediator::isServerError($response);

        if ($isClientError || $isServerError) {
            $content = ResponseMediator::getContent($response);
            $rateLimit = ResponseMediator::getApiRateLimit($response);

            if (null !== $rateLimit['remaining'] && '' !== $rateLimit['remaining'] && 1 > $rateLimit['remaining']) {
                throw new ApiLimitExceedException($rateLimit);
            }

            if (400 === $response->getStatusCode()) {
                throw new BadRequestException($content);
            }

            if (401 === $response->getStatusCode()) {
                throw new AuthenticationRequiredException();
            }

            if (404 === $response->getStatusCode()) {
                throw new NotFoundException();
            }

            if (is_array($content) && isset($content['message'])) {
                if (400 == $response->getStatusCode()) {
                    throw new ErrorException($content['message'], 400);
                } elseif (422 == $response->getStatusCode() && isset($content['errors'])) {
                    $errors = array();
                    foreach ($content['errors'] as $error) {
                        switch ($error['code']) {
                            case 'missing':
                                $errors[] = sprintf('The %s %s does not exist, for resource "%s"', $error['field'], $error['value'], $error['resource']);
                                break;

                            case 'missing_field':
                                $errors[] = sprintf('Field "%s" is missing, for resource "%s"', $error['field'], $error['resource']);
                                break;

                            case 'invalid':
                                $errors[] = sprintf('Field "%s" is invalid, for resource "%s"', $error['field'], $error['resource']);
                                break;

                            case 'already_exists':
                                $errors[] = sprintf('Field "%s" already exists, for resource "%s"', $error['field'], $error['resource']);
                                break;

                            default:
                                $errors[] = $error['message'];
                                break;

                        }
                    }

                    throw new ValidationFailedException('Validation Failed: ' . implode(', ', $errors), 422);
                }
            }

            throw new RuntimeException(isset($content['message']) ? $content['message'] : $content, $response->getStatusCode());
        };
    }
}
