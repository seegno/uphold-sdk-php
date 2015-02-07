<?php

namespace Bitreserve\Tests\HttpClient\Handler;

use Bitreserve\Exception\RuntimeException;
use Bitreserve\HttpClient\Handler\ErrorHandler;
use GuzzleHttp\Exception\RequestException;

class ErrorHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldCallOnRequestExceptionWhenARequestExceptionIsGiven()
    {
        $request = $this->getRequestMock();
        $response = $this->getResponseMock();

        $exception = new RequestException('Request exception', $request, $response);

        $errorHandler = $this->getMockBuilder('Bitreserve\HttpClient\Handler\ErrorHandler')
            ->disableOriginalConstructor()
            ->setMethods(array('onRequestException'))
            ->getMock();

        $errorHandler->expects($this->once())
            ->method('onRequestException')
            ->with($exception);

        $errorHandler->onException($exception);
    }

    /**
     * @test
     * @expectedException Bitreserve\Exception\LogicException
     */
    public function shouldThrowLogicExceptionWhenALogicExceptionIsGiven()
    {
        $errorHandler = new ErrorHandler();
        $errorHandler->onException(new \LogicException('Request exception'));
    }

    /**
     * @test
     * @expectedException Bitreserve\Exception\RuntimeException
     */
    public function shouldThrowRuntimeExceptionWhenARuntimeExceptionIsGiven()
    {
        $errorHandler = new ErrorHandler();
        $errorHandler->onException(new RuntimeException('Runtime error'));
    }

    /**
     * @test
     * @expectedException Bitreserve\Exception\RuntimeException
     */
    public function shouldThrowRuntimeExceptionWhenARequestExceptionOrLogicExceptionIsNotGiven()
    {
        $errorHandler = new ErrorHandler();
        $errorHandler->onException(new \Exception('Request exception'));
    }

    /**
     * @test
     * @expectedException Bitreserve\Exception\BadRequestException
     */
    public function shouldThrowBadRequestExceptionWhenStatusCodeIs400()
    {
        $request = $this->getRequestMock();
        $response = $this->getResponseMock(400);

        $errorHandler = new ErrorHandler();
        $errorHandler->onException(new RequestException('400 error', $request, $response));
    }

    /**
     * @test
     * @expectedException Bitreserve\Exception\AuthenticationRequiredException
     */
    public function shouldThrowAuthenticationRequiredExceptionWhenStatusCodeIs401()
    {
        $request = $this->getRequestMock();
        $response = $this->getResponseMock(401);

        $response->expects($this->once())
            ->method('getHeader')
            ->with('X-Bitreserve-OTP')
            ->will($this->returnValue(null));

        $errorHandler = new ErrorHandler();
        $errorHandler->onException(new RequestException('401 error', $request, $response));
    }

    /**
     * @test
     * @expectedException Bitreserve\Exception\TwoFactorAuthenticationRequiredException
     */
    public function shouldThrowTwoFactorAuthenticationRequiredExceptionWhenStatusCodeIs401()
    {
        $request = $this->getRequestMock();
        $response = $this->getResponseMock(401);

        $response->expects($this->once())
            ->method('getHeader')
            ->with('X-Bitreserve-OTP')
            ->will($this->returnVAlue('required'));

        $errorHandler = new ErrorHandler();
        $errorHandler->onException(new RequestException('401 error', $request, $response));
    }

    /**
     * @test
     * @expectedException Bitreserve\Exception\RuntimeException
     */
    public function shouldThrowRuntimeExceptionWhenStatusCodeIs403()
    {
        $request = $this->getRequestMock();
        $response = $this->getResponseMock(403);

        $errorHandler = new ErrorHandler();
        $errorHandler->onException(new RequestException('403 error', $request, $response));
    }

    /**
     * @test
     * @expectedException Bitreserve\Exception\NotFoundException
     */
    public function shouldThrowNotFoundExceptionWhenStatusCodeIs404()
    {
        $request = $this->getRequestMock();
        $response = $this->getResponseMock(404);

        $errorHandler = new ErrorHandler();
        $errorHandler->onException(new RequestException('404 error', $request, $response));
    }

    /**
     * @test
     * @expectedException Bitreserve\Exception\ApiLimitExceedException
     */
    public function shouldThrowApiLimitExceedException()
    {
        $request = $this->getRequestMock();
        $response = $this->getResponseMock(429);

        $response->expects($this->exactly(3))
            ->method('getHeader')
            ->withConsecutive(
                array('X-RateLimit-Limit'),
                array('X-RateLimit-Remaining'),
                array('X-RateLimit-Reset')
            )
            ->will($this->onConsecutiveCalls(300, 0, 1384377793));

        $errorHandler = new ErrorHandler();
        $errorHandler->onException(new RequestException('API rate limit error', $request, $response));
    }

    /**
     * @test
     * @expectedException Bitreserve\Exception\RuntimeException
     */
    public function shouldThrowRuntimeExceptionWhenStatusCodeIs500()
    {
        $request = $this->getRequestMock();
        $response = $this->getResponseMock(500);

        $errorHandler = new ErrorHandler();
        $errorHandler->onException(new RequestException('500 error', $request, $response));
    }

    protected function getRequestMock()
    {
        return $this->getMockBuilder('GuzzleHttp\Message\Request')
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function getResponseMock($statusCode = null)
    {
        $response = $this->getMockBuilder('GuzzleHttp\Message\Response')
            ->disableOriginalConstructor()
            ->getMock();

        if (null === $statusCode) {
            return $response;
        }

        $response->expects($this->any())
            ->method('getStatusCode')
            ->will($this->returnValue($statusCode));

        return $response;
    }
}
