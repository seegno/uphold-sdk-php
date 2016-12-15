<?php

namespace Uphold\Tests\Unit\HttpClient\Handler;

use GuzzleHttp\Exception\ConnectException as GuzzleConnectException;
use GuzzleHttp\Exception\RequestException;
use Seegno\TestBundle\TestCase\BaseTestCase;
use Uphold\Exception\RuntimeException;
use Uphold\HttpClient\Handler\ErrorHandler;
use Uphold\HttpClient\Message\Response;

/**
 * ErrorHandlerTest.
 */
class ErrorHandlerTest extends BaseTestCase
{
    /**
     * @test
     */
    public function shouldCallOnRequestExceptionWhenARequestExceptionIsGiven()
    {
        $request = $this->getRequestMock();

        $response = $this
            ->getMockBuilder('Uphold\HttpClient\Message\Response')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $exception = new RequestException('Request exception', $request, $response);

        $errorHandler = $this
            ->getMockBuilder('Uphold\HttpClient\Handler\ErrorHandler')
            ->disableOriginalConstructor()
            ->setMethods(array('onRequestException'))
            ->getMock()
        ;

        $errorHandler->expects($this->once())
            ->method('onRequestException')
            ->with($exception);

        $errorHandler->onException($exception);
    }

    /**
     * @test
     * @expectedException Uphold\Exception\ConnectException
     */
    public function shouldThrowConnectExceptionWhenAGuzzleConnectExceptionIsGiven()
    {
        $request = $this->getRequestMock();
        $exception = new GuzzleConnectException('Could not resolve host: foobar.com', $request);

        $errorHandler = new ErrorHandler();
        $errorHandler->onException($exception);
    }

    /**
     * @test
     * @expectedException Uphold\Exception\LogicException
     */
    public function shouldThrowLogicExceptionWhenALogicExceptionIsGiven()
    {
        $errorHandler = new ErrorHandler();
        $errorHandler->onException(new \LogicException('Request exception'));
    }

    /**
     * @test
     * @expectedException Uphold\Exception\RuntimeException
     */
    public function shouldThrowRuntimeExceptionWhenARuntimeExceptionIsGiven()
    {
        $errorHandler = new ErrorHandler();
        $errorHandler->onException(new RuntimeException('Runtime error'));
    }

    /**
     * @test
     * @expectedException Uphold\Exception\RuntimeException
     */
    public function shouldThrowRuntimeExceptionWhenARequestExceptionOrLogicExceptionIsNotGiven()
    {
        $errorHandler = new ErrorHandler();
        $errorHandler->onException(new \Exception('Request exception'));
    }

    /**
     * @test
     * @expectedException Uphold\Exception\RuntimeException
     * @expectedExceptionMessage foobar
     */
    public function shouldThrowRuntimeExceptionWhenARequestExceptionReceivesAnEmptyResponse()
    {
        $request = $this->getRequestMock();

        $errorHandler = new ErrorHandler();
        $errorHandler->onException(new RequestException('foobar', $request, null));
    }

    /**
     * @test
     * @expectedException Uphold\Exception\BadRequestException
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
     * @expectedException Uphold\Exception\AuthenticationRequiredException
     */
    public function shouldThrowAuthenticationRequiredExceptionWhenStatusCodeIs401()
    {
        $request = $this->getRequestMock();

        $response = $this
            ->getMockBuilder('Uphold\HttpClient\Message\Response')
            ->setConstructorArgs(array(401))
            ->setMethods(array('getHeader'))
            ->getMock()
        ;

        $response
            ->expects($this->once())
            ->method('getHeader')
            ->with('OTP-Token')
            ->will($this->returnValue(null))
        ;

        $errorHandler = new ErrorHandler();
        $errorHandler->onException(new RequestException('401 error', $request, $response));
    }

    /**
     * @test
     * @expectedException Uphold\Exception\TwoFactorAuthenticationRequiredException
     */
    public function shouldThrowTwoFactorAuthenticationRequiredExceptionWhenStatusCodeIs401()
    {
        $request = $this->getRequestMock();

        $response = $this
            ->getMockBuilder('Uphold\HttpClient\Message\Response')
            ->setConstructorArgs(array(401))
            ->setMethods(array('getHeader'))
            ->getMock()
        ;

        $response
            ->expects($this->once())
            ->method('getHeader')
            ->with('OTP-Token')
            ->will($this->returnValue('required'))
        ;

        $errorHandler = new ErrorHandler();
        $errorHandler->onException(new RequestException('401 error', $request, $response));
    }

    /**
     * @test
     * @expectedException Uphold\Exception\RuntimeException
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
     * @expectedException Uphold\Exception\NotFoundException
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
     * @expectedException Uphold\Exception\ApiLimitExceedException
     */
    public function shouldThrowApiLimitExceedException()
    {
        $request = $this->getRequestMock();

        $response = $this
            ->getMockBuilder('Uphold\HttpClient\Message\Response')
            ->setConstructorArgs(array(429))
            ->setMethods(array('getHeader'))
            ->getMock()
        ;

        $response
            ->expects($this->exactly(3))
            ->method('getHeader')
            ->withConsecutive(
                array('Rate-Limit-Total'),
                array('Rate-Limit-Remaining'),
                array('Rate-Limit-Reset')
            )
            ->will($this->onConsecutiveCalls(300, 0, 1384377793))
        ;

        $errorHandler = new ErrorHandler();
        $errorHandler->onException(new RequestException('API rate limit error', $request, $response));
    }

    /**
     * @test
     * @expectedException Uphold\Exception\RuntimeException
     */
    public function shouldThrowRuntimeExceptionWhenStatusCodeIs500()
    {
        $request = $this->getRequestMock();
        $response = new Response(500);

        $errorHandler = new ErrorHandler();
        $errorHandler->onException(new RequestException('500 error', $request, $response));
    }

    /**
     * Get `Request` mock.
     *
     * @return Request
     */
    protected function getRequestMock()
    {
        return $this
            ->getMockBuilder('GuzzleHttp\Message\Request')
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }
}
