<?php

namespace Bitreserve\Tests\Model;

use Bitreserve\BitreserveClient;
use Bitreserve\Model\Token;

/**
 * TokenTest.
 */
class TokenTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnInstanceOfToken()
    {
        $client = $this->getBitreserveClientMock();

        $client->expects($this->once())
            ->method('getOption')
            ->with('bearer')
            ->will($this->returnValue('token'));

        $token = new Token($client);

        $this->assertInstanceOf('Bitreserve\BitreserveClient', $token->getClient());
    }

    /**
     * @test
     */
    public function shouldReturnUser()
    {
        $data = array('username' => 'foobar');

        $response = $this->getResponseMock($data);

        $client = $this->getBitreserveClientMock();

        $client->expects($this->once())
            ->method('getOption')
            ->with('bearer')
            ->will($this->returnValue('token'));

        $client->expects($this->once())
            ->method('get')
            ->with('/me')
            ->will($this->returnValue($response));

        $token = new Token($client);

        $user = $token->getUser();

        $this->assertInstanceOf('Bitreserve\Model\User', $user);
        $this->assertEquals($data['username'], $user->getUsername());
    }

    protected function getModelClass()
    {
        return 'Bitreserve\Model\Token';
    }
}
