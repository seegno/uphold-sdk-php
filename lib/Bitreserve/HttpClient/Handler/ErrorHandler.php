<?php

namespace Bitreserve\HttpClient\Handler;

use Bitreserve\Exception\ApiLimitExceedException;
use Bitreserve\Exception\AuthenticationRequiredException;
use Bitreserve\Exception\BadRequestException;
use Bitreserve\Exception\LogicException;
use Bitreserve\Exception\NotFoundException;
use Bitreserve\Exception\RuntimeException;
use Bitreserve\Exception\TwoFactorAuthenticationRequiredException;
use Bitreserve\HttpClient\Message\ResponseMediator;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Message\Response;

/**
 * ErrorHandler.
 */
class ErrorHandler
{
    /**
     * @var $options
     */
    private $options;

    /**
     * Constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $this->options = $options;
    }

    /**
     * Handles different types of exceptions.
     *
     * @param \Exception $e The exception.
     *
     * @return void
     */
    public function onException(\Exception $e)
    {
        if ($e instanceOf RequestException) {
            return $this->onRequestException($e);
        }

        if ($e instanceOf \LogicException) {
            throw new LogicException($e->getMessage(), $e->getCode());
        }

        throw new RuntimeException($e->getMessage(), $e->getCode());
    }

    /**
     * Handles a Request Exception.
     *
     * @param RequestException $e The request exception.
     *
     * @return void
     */
    protected function onRequestException(RequestException $e)
    {
        $request = $e->getRequest();
        $response = $e->getResponse();
        $statusCode = $response->getStatusCode();

        $isClientError = ResponseMediator::isClientError($response);
        $isServerError = ResponseMediator::isServerError($response);

        if ($isClientError || $isServerError) {
            $content = ResponseMediator::getContent($response);

            $error = ResponseMediator::getError($response);
            $description = ResponseMediator::getErrorDescription($response);

            if (400 === $statusCode) {
                throw new BadRequestException($description, $error, $statusCode, $response, $request);
            }

            if (401 === $statusCode) {
                $otp = (string) $response->getHeader('X-Bitreserve-OTP');

                if ('required' === $otp) {
                    $description = 'Two factor authentication is enabled on this account';

                    throw new TwoFactorAuthenticationRequiredException($description, $error, $statusCode, $response, $request);
                }

                throw new AuthenticationRequiredException($description, $error, $statusCode, $response, $request);
            }

            if (404 === $statusCode) {
                $description = sprintf('Object or route not found: %s', $request->getPath());

                throw new NotFoundException($description, 'not_found', $statusCode, $response, $request);
            }

            if (429 === $statusCode) {
                $rateLimit = ResponseMediator::getApiRateLimit($response);

                $description = sprintf('You have reached Bitreserve API limit. API limit is: %s. Your remaining requests will be reset at %s.', $rateLimit['limit'], date('Y-m-d H:i:s', $rateLimit['reset']));

                throw new ApiLimitExceedException($description, $error, $statusCode, $response, $request);
            }

            throw new RuntimeException($description, $error, $statusCode, $response, $request);
        }
    }
}
