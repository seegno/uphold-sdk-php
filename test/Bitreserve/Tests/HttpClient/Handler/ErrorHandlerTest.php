<?php

namespace Bitreserve\Tests\HttpClient\Handler;

use Bitreserve\Exception\RuntimeException;
use Bitreserve\HttpClient\Handler\ErrorHandler;
use Bitreserve\HttpClient\Message\Response;
use GuzzleHttp\Exception\RequestException;

/**
 * ErrorHandlerTest.
 */
class ErrorHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldCallOnRequestExceptionWhenARequestExceptionIsGiven()
    {
        $request = $this->getRequestMock();
        $response = $this->getMockBuilder('Bitreserve\HttpClient\Message\Response')
            ->disableOriginalConstructor()
            ->getMock();

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
        $response = new Response(400);

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
        $response = $this->getMockBuilder('Bitreserve\HttpClient\Message\Response')
            ->setConstructorArgs(array(401))
            ->setMethods(array('getHeader'))
            ->getMock();

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
        $response = $this->getMockBuilder('Bitreserve\HttpClient\Message\Response')
            ->setConstructorArgs(array(401))
            ->setMethods(array('getHeader'))
            ->getMock();

        $response->expects($this->once())
            ->method('getHeader')
            ->with('X-Bitreserve-OTP')
            ->will($this->returnValue('required'));

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
        $response = new Response(403);

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
        $response = new Response(404);

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
        $response = $this->getMockBuilder('Bitreserve\HttpClient\Message\Response')
            ->setConstructorArgs(array(429))
            ->setMethods(array('getHeader'))
            ->getMock();

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
        $response = new Response(500);

        $errorHandler = new ErrorHandler();
        $errorHandler->onException(new RequestException('500 error', $request, $response));
    }

    protected function getRequestMock()
    {
        return $this->getMockBuilder('GuzzleHttp\Message\Request')
            ->disableOriginalConstructor()
            ->getMock();
    }
}
