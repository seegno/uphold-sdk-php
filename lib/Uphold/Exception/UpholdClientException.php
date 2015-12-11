<?php

namespace Uphold\Exception;

/**
 * UpholdException.
 */
class UpholdClientException extends \Exception
{
    /**
     * Error.
     *
     * @var mixed
     */
    protected $error;

    /**
     * HttpCode.
     *
     * @var int
     */
    protected $httpCode;

    /**
     * Message.
     *
     * @var string.
     */
    protected $message;

    /**
     * Request.
     *
     * @var Request
     */
    protected $request;

    /**
     * Response.
     *
     * @var Response
     */
    protected $response;

    /**
     * Constructor.
     *
     * @param string $message  Exception message.
     * @param string $error  Exception error.
     * @param string $httpCode Http error code.
     * @param mixed  $response The response.
     * @param mixed  $request  The request.
     */
    public function __construct($message, $error = null, $httpCode = null, $response = null, $request = null)
    {
        parent::__construct($message);

        $this->message = $message;
        $this->error = $error;
        $this->httpCode = $httpCode;
        $this->response = $response;
        $this->request = $request;
    }

    /**
     * Get error.
     *
     * @return string The error string.
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Get the httpCode.
     *
     * @return int The http code.
     */
    public function getHttpCode()
    {
        return $this->httpCode;
    }

    /**
     * Get the response.
     *
     * @return mixed The response object.
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Get the request.
     *
     * @return mixed The request object.
     */
    public function getRequest()
    {
        return $this->request;
    }
}
