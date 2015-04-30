<?php

namespace Bitreserve\Tests\Unit\Model;

use Bitreserve\BitreserveClient;
use Bitreserve\Model\User;

/**
 * UserTest.
 */
class UserTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnInstanceOfUser()
    {
        $client = $this->getBitreserveClientMock();

        $data = array('firstName' => $this->getFaker()->firstName);

        $user = new User($client, $data);

        $this->assertInstanceOf('Bitreserve\BitreserveClient', $user->getClient());
    }

    /**
     * @test
     */
    public function shouldPerformAUserUpdate()
    {
        $data = array('firstName' => $this->getFaker()->firstName, 'lastName' => $this->getFaker()->lastName);

        $response = $this->getResponseMock($data);

        $client = $this->getBitreserveClientMock();

        $client
            ->expects($this->once())
            ->method('patch')
            ->with('/me',$data)
            ->will($this->returnValue($response))
        ;

        $user = new User($client, $data);

        $user->update($data);

        $this->assertEquals($data['firstName'], $user->getFirstName());
        $this->assertEquals($data['lastName'], $user->getLastName());
    }

    /**
     * @test
     */
    public function shouldReturnUsername()
    {
        $data = array('username' => $this->getFaker()->username);

        $client = $this->getBitreserveClientMock();

        $user = new User($client, $data);

        $this->assertEquals($data['username'], $user->getUsername());
    }

    /**
     * @test
     */
    public function shouldReturnEmail()
    {
        $data = array('email' => $this->getFaker()->email);

        $client = $this->getBitreserveClientMock();

        $user = new User($client, $data);

        $this->assertEquals($data['email'], $user->getEmail());
    }

    /**
     * @test
     */
    public function shouldReturnFirstName()
    {
        $data = array('firstName' => $this->getFaker()->firstName);

        $client = $this->getBitreserveClientMock();

        $user = new User($client, $data);

        $this->assertEquals($data['firstName'], $user->getFirstName());
    }

    /**
     * @test
     */
    public function shouldReturnLastName()
    {
        $data = array('lastName' => $this->getFaker()->lastName);

        $client = $this->getBitreserveClientMock();

        $user = new User($client, $data);

        $this->assertEquals($data['lastName'], $user->getLastName());
    }

    /**
     * @test
     */
    public function shouldReturnName()
    {
        $data = array('name' => $this->getFaker()->name);

        $client = $this->getBitreserveClientMock();

        $user = new User($client, $data);

        $this->assertEquals($data['name'], $user->getName());
    }

    /**
     * @test
     */
    public function shouldReturnCountry()
    {
        $data = array('country' => $this->getFaker()->countryCode);

        $client = $this->getBitreserveClientMock();

        $user = new User($client, $data);

        $this->assertEquals($data['country'], $user->getCountry());
    }

    /**
     * @test
     */
    public function shouldReturnCurrencies()
    {
        $data = array('currencies' => array('BTC', 'EUR', 'USD'));

        $client = $this->getBitreserveClientMock();

        $user = new User($client, $data);

        $this->assertEquals($data['currencies'], $user->getCurrencies());
    }

    /**
     * @test
     */
    public function shouldReturnState()
    {
        $data = array('state' => $this->getFaker()->state);

        $client = $this->getBitreserveClientMock();

        $user = new User($client, $data);

        $this->assertEquals($data['state'], $user->getState());
    }

    /**
     * @test
     */
    public function shouldReturnStatus()
    {
        $data = array('status' => array(
            'email' => 'ok',
            'phone' => 'pending',
        ));

        $client = $this->getBitreserveClientMock();

        $user = new User($client, $data);

        $this->assertEquals($data['status'], $user->getStatus());
    }

    /**
     * @test
     */
    public function shouldReturnSettings()
    {
        $data = array('settings' => array(
            'currency' => $this->getFaker()->currencyCode,
            'theme' => 'minimalistic',
        ));

        $response = $this->getResponseMock($data);

        $client = $this->getBitreserveClientMock();

        $client
            ->expects($this->once())
            ->method('get')
            ->with('/me')
            ->will($this->returnValue($response))
        ;

        $user = new User($client, $data);

        $this->assertEquals($data['settings'], $user->getSettings());
    }

    /**
     * @test
     */
    public function shouldReturnPhones()
    {
        $data = array(
            'id' => $this->getFaker()->uuid,
            'verified' => $this->getFaker()->boolean,
            'primary' => $this->getFaker()->boolean,
            'e164Masked' => '+XXXXXXXXX04',
            'nationalMasked' => '(XXX) XXX-XX04',
            'internationalMasked' => '+X XXX-XXX-XX04',
        );

        $response = $this->getResponseMock($data);

        $client = $this->getBitreserveClientMock();

        $client
            ->expects($this->once())
            ->method('get')
            ->with('/me/phones')
            ->will($this->returnValue($response))
        ;

        $user = new User($client, $data);

        $phones = $user->getPhones();

        $this->assertEquals($data, $phones);
    }

    /**
     * @test
     */
    public function shouldReturnContacts()
    {
        $data = array(array(
            'id' => $this->getFaker()->uuid,
            'firstName' => $this->getFaker()->firstName,
            'lastName' => $this->getFaker()->lastName,
        ));

        $response = $this->getResponseMock($data);

        $client = $this->getBitreserveClientMock();

        $client
            ->expects($this->once())
            ->method('get')
            ->with('/me/contacts')
            ->will($this->returnValue($response))
        ;

        $user = new User($client, $data);

        $contacts = $user->getContacts();

        foreach ($contacts as $contact) {
            $this->assertInstanceOf('Bitreserve\Model\Contact', $contact);
        }
    }

    /**
     * @test
     */
    public function shouldReturnBalances()
    {
        $data = array('balances' => array(
            'currencies' => array(
                'EUR' => array(
                    'balance' => $this->getFaker()->randomFloat(2),
                    'amount' => $this->getFaker()->randomFloat(2),
                    'rate' => $this->getFaker()->randomFloat(2, 1, 2),
                    'currency' => 'EUR',
                ),
                'USD' => array(
                    'balance' => $this->getFaker()->randomFloat(2),
                    'amount' => $this->getFaker()->randomFloat(2),
                    'rate' => $this->getFaker()->randomFloat(2, 1, 2),
                    'currency' => 'EUR',
                ),
                'XAU' => array(
                    'balance' => $this->getFaker()->randomFloat(2),
                    'amount' => $this->getFaker()->randomFloat(2),
                    'rate' => $this->getFaker()->randomFloat(2),
                    'currency' => 'EUR',
                ),
            ),
            'total' => $this->getFaker()->randomFloat(2),
        ));

        $response = $this->getResponseMock($data);

        $client = $this->getBitreserveClientMock();

        $client
            ->expects($this->once())
            ->method('get')
            ->with('/me')
            ->will($this->returnValue($response))
        ;

        $user = new User($client, $data);

        $balances = $user->getBalances();

        $this->assertEquals($data['balances']['currencies'], $balances);
    }

    /**
     * @test
     * @dataProvider getCurrenciesProvider
     */
    public function shouldReturnOneBalanceByCurrency($currency)
    {
        $data = array('balances' => array(
            'currencies' => array(
                'EUR' => array(
                    'balance' => $this->getFaker()->randomFloat(2),
                    'amount' => $this->getFaker()->randomFloat(2),
                    'rate' => $this->getFaker()->randomFloat(2, 1, 2),
                    'currency' => 'EUR',
                ),
                'USD' => array(
                    'balance' => $this->getFaker()->randomFloat(2),
                    'amount' => $this->getFaker()->randomFloat(2),
                    'rate' => $this->getFaker()->randomFloat(2, 1, 2),
                    'currency' => 'EUR',
                ),
                'XAU' => array(
                    'balance' => $this->getFaker()->randomFloat(2),
                    'amount' => $this->getFaker()->randomFloat(2),
                    'rate' => $this->getFaker()->randomFloat(2),
                    'currency' => 'EUR',
                ),
            ),
            'total' => $this->getFaker()->randomFloat(2),
        ));

        $response = $this->getResponseMock($data);

        $client = $this->getBitreserveClientMock();

        $client
            ->expects($this->once())
            ->method('get')
            ->with('/me')
            ->will($this->returnValue($response))
        ;

        $user = new User($client, $data);

        $balances = $user->getBalanceByCurrency($currency);

        $this->assertEquals($data['balances']['currencies'][$currency], $balances);
    }

    /**
     * @test
     */
    public function shouldReturnTotalBalance()
    {
        $data = array(
            'balances' => array(
                'currencies' => array(
                    'EUR' => array(
                        'balance' => $this->getFaker()->randomFloat(2),
                        'amount' => $this->getFaker()->randomFloat(2),
                        'rate' => $this->getFaker()->randomFloat(2, 1, 2),
                        'currency' => 'EUR',
                    ),
                    'USD' => array(
                        'balance' => $this->getFaker()->randomFloat(2),
                        'amount' => $this->getFaker()->randomFloat(2),
                        'rate' => $this->getFaker()->randomFloat(2, 1, 2),
                        'currency' => 'EUR',
                    ),
                    'XAU' => array(
                        'balance' => $this->getFaker()->randomFloat(2),
                        'amount' => $this->getFaker()->randomFloat(2),
                        'rate' => $this->getFaker()->randomFloat(2),
                        'currency' => 'EUR',
                    ),
                ),
                'total' => $this->getFaker()->randomFloat(2),
            ),
            'settings' => array(
                'currency' => $this->getFaker()->currencyCode,
        ));

        $response = $this->getResponseMock($data);

        $client = $this->getBitreserveClientMock();

        $client
            ->expects($this->once())
            ->method('get')
            ->with('/me')
            ->will($this->returnValue($response))
        ;

        $user = new User($client, $data);

        $this->assertEquals(
            array(
                'amount' => $data['balances']['total'],
                'currency' => $data['settings']['currency'],
            ),
            $user->getTotalBalance()
        );
    }

    /**
     * @test
     */
    public function shouldReturnCards()
    {
        $data = array(array(
            'id' => $this->getFaker()->randomDigitNotNull,
            'label' => $this->getFaker()->sentence(2),
            'currency' => $this->getFaker()->currencyCode,
            'balance' => $this->getFaker()->randomFloat(2),
        ), array(
            'id' => $this->getFaker()->randomDigitNotNull,
            'label' => $this->getFaker()->sentence(2),
            'currency' => $this->getFaker()->currencyCode,
            'balance' => $this->getFaker()->randomFloat(2),
        ));

        $response = $this->getResponseMock($data);

        $client = $this->getBitreserveClientMock();

        $client
            ->expects($this->once())
            ->method('get')
            ->with('/me/cards')
            ->will($this->returnValue($response))
        ;

        $user = new User($client, array('username' => $this->getFaker()->username));

        $cards = $user->getCards();

        $this->assertCount(count($data), $cards);

        foreach ($cards as $card) {
            $this->assertInstanceOf('Bitreserve\Model\Card', $card);
        }
    }

    /**
     * @test
     */
    public function shouldReturnCardsByCurrency()
    {
        $expectedCurrency = $this->getFaker()->currencyCode;

        $data = array(array(
            'id' => $this->getFaker()->randomDigitNotNull,
            'label' => $this->getFaker()->sentence(2),
            'currency' =>  $expectedCurrency,
            'balance' => $this->getFaker()->randomFloat(2),
        ), array(
            'id' => $this->getFaker()->randomDigitNotNull,
            'label' => $this->getFaker()->sentence(2),
            'currency' => $this->getFaker()->currencyCode,
            'balance' => $this->getFaker()->randomFloat(2),
        ));

        $response = $this->getResponseMock($data);

        $client = $this->getBitreserveClientMock();

        $client
            ->expects($this->once())
            ->method('get')
            ->with('/me/cards')
            ->will($this->returnValue($response))
        ;

        $user = new User($client, array('username' => $this->getFaker()->username));

        $cards = $user->getCardsByCurrency($expectedCurrency);

        foreach ($cards as $card) {
            $this->assertInstanceOf('Bitreserve\Model\Card', $card);
            $this->assertEquals($expectedCurrency, $card->getCurrency());
        }
    }

    /**
     * @test
     */
    public function shouldCreateNewCard()
    {
        $data = array(
            'id' => $this->getFaker()->randomDigitNotNull,
            'label' => $this->getFaker()->sentence(2),
            'currency' => $this->getFaker()->currencyCode,
        );

        $response = $this->getResponseMock($data);

        $client = $this->getBitreserveClientMock();

        $client
            ->expects($this->once())
            ->method('post')
            ->with('/me/cards')
            ->will($this->returnValue($response))
        ;

        $user = new User($client, array('username' => $this->getFaker()->username));

        $card = $user->createCard($data['label'], $data['currency']);

        $this->assertInstanceOf('Bitreserve\Model\Card', $card);

        $this->assertEquals($data['id'], $card->getId());
        $this->assertEquals($data['label'], $card->getLabel());
        $this->assertEquals($data['currency'], $card->getCurrency());
    }

    /**
     * @test
     */
    public function shouldReturnOneCard()
    {
        $data = array(
            'id' => $this->getFaker()->randomDigitNotNull,
            'address' => array(
                'bitcoin' => '145ZeN94MAtTmEgvhXEch3rRgrs7BdD2cY'
            ),
            'label' => $this->getFaker()->sentence(2),
            'currency' => $this->getFaker()->currencyCode,
            'balance' => $this->getFaker()->randomFloat(2),
            'available' => $this->getFaker()->randomFloat(2),
            'lastTransactionAt' => $this->getFaker()->iso8601,
            'position' => $this->getFaker()->randomDigitNotNull,
            'addresses' => array(array(
                'id' => '145ZeN94MAtTmEgvhXEch3rRgrs7BdD2cY',
                'network' => 'bitcoin',
            )),
        );

        $response = $this->getResponseMock($data);

        $client = $this->getBitreserveClientMock();

        $client
            ->expects($this->once())
            ->method('get')
            ->with(sprintf('/me/cards/%s', $data['id']))
            ->will($this->returnValue($response))
        ;

        $user = new User($client, array('username' => $this->getFaker()->username));

        $card = $user->getCardById($data['id']);

        $this->assertInstanceOf('Bitreserve\Model\Card', $card);
        $this->assertEquals($data['id'], $card->getId());
    }

    /**
     * @test
     */
    public function shouldReturnTransactions()
    {
        $data = array(array(
            'id' => $this->getFaker()->randomDigitNotNull,
            'status' => 'pending',
        ), array(
            'id' => $this->getFaker()->randomDigitNotNull,
            'status' => 'completed',
        ));

        $response = $this->getResponseMock($data);

        $client = $this->getBitreserveClientMock();

        $client
            ->expects($this->once())
            ->method('get')
            ->with('/me/transactions')
            ->will($this->returnValue($response))
        ;

        $user = new User($client, array('username' => $this->getFaker()->username));

        $pager = $user->getTransactions();

        $this->assertInstanceOf('Bitreserve\Paginator\Paginator', $pager);

        $transactions = $pager->getNext();

        $this->assertCount(count($data), $transactions);

        foreach ($transactions as $transaction) {
            $this->assertInstanceOf('Bitreserve\Model\Transaction', $transaction);
        }
    }

    /**
     * @test
     * @expectedException Bitreserve\Exception\AuthenticationRequiredException
     * @expectedExceptionMessage Missing bearer authorization
     */
    public function shouldThrowAuthenticationRequiredExceptionOnRevokeTokenWhenBearerTokenIsMissing()
    {
        $client = $this->getBitreserveClientMock();

        $client
            ->expects($this->once())
            ->method('getOption')
            ->with('bearer')
            ->will($this->returnValue(null))
        ;

        $user = new User($client, array('username' => 'foobar'));

        $user->revokeToken();
    }

    /**
     * @test
     */
    public function shouldRevokeToken()
    {
        $bearerToken = 'foobar';
        $expectedResult = 'qux';

        $client = $this->getBitreserveClientMock();

        $client
            ->expects($this->once())
            ->method('getOption')
            ->with('bearer')
            ->will($this->returnValue($bearerToken))
        ;

        $client
            ->expects($this->once())
            ->method('get')
            ->with(sprintf('/me/tokens/%s', $bearerToken))
            ->will($this->returnValue($expectedResult))
        ;

        $user = new User($client, array('username' => 'foobar'));

        $this->assertEquals($expectedResult, $user->revokeToken());
    }

    /**
     * Get currencies provider.
     *
     * @return array
     */
    public function getCurrenciesProvider()
    {
        return array(
            array('EUR'),
            array('USD'),
            array('XAU'),
        );
    }

    /**
     * Get model class.
     *
     * @return string
     */
    protected function getModelClass()
    {
        return 'Bitreserve\Model\User';
    }
}
