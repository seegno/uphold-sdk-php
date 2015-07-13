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

        $data = array('firstName' => 'foo');

        $user = new User($client, $data);

        $this->assertInstanceOf('Bitreserve\BitreserveClient', $user->getClient());
    }

    /**
     * @test
     */
    public function shouldPerformAUserUpdate()
    {
        $data = array('firstName' => 'foo', 'lastName' => 'bar');

        $response = $this->getResponseMock($data);

        $client = $this->getBitreserveClientMock();
        $client->expects($this->once())
            ->method('patch')
            ->with('/me',$data)
            ->will($this->returnValue($response));

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
        $data = array('username' => 'foobar');

        $client = $this->getBitreserveClientMock();

        $user = new User($client, $data);

        $this->assertEquals($data['username'], $user->getUsername());
    }

    /**
     * @test
     */
    public function shouldReturnEmail()
    {
        $data = array('email' => 'foo@bar.com');

        $client = $this->getBitreserveClientMock();

        $user = new User($client, $data);

        $this->assertEquals($data['email'], $user->getEmail());
    }

    /**
     * @test
     */
    public function shouldReturnFirstName()
    {
        $data = array('firstName' => 'foo');

        $client = $this->getBitreserveClientMock();

        $user = new User($client, $data);

        $this->assertEquals($data['firstName'], $user->getFirstName());
    }

    /**
     * @test
     */
    public function shouldReturnLastName()
    {
        $data = array('lastName' => 'bar');

        $client = $this->getBitreserveClientMock();

        $user = new User($client, $data);

        $this->assertEquals($data['lastName'], $user->getLastName());
    }

    /**
     * @test
     */
    public function shouldReturnName()
    {
        $data = array('name' => 'Foobar');

        $client = $this->getBitreserveClientMock();

        $user = new User($client, $data);

        $this->assertEquals($data['name'], $user->getName());
    }

    /**
     * @test
     */
    public function shouldReturnCountry()
    {
        $data = array('country' => 'US');

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
        $data = array('state' => 'CA');

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
            'currency' => 'USD',
            'theme' => 'minimalistic',
        ));

        $response = $this->getResponseMock($data);

        $client = $this->getBitreserveClientMock();
        $client->expects($this->once())
            ->method('get')
            ->with('/me')
            ->will($this->returnValue($response));

        $user = new User($client, $data);

        $this->assertEquals($data['settings'], $user->getSettings());
    }

    /**
     * @test
     */
    public function shouldReturnPhones()
    {
        $data = array(
            'id' => '1d78aeb5-43ac-4ee8-8d28-1291b5d8355c',
            'verified' => 'true',
            'primary' => 'true',
            'e164Masked' => '+XXXXXXXXX04',
            'nationalMasked' => '(XXX) XXX-XX04',
            'internationalMasked' => '+X XXX-XXX-XX04',
        );

        $response = $this->getResponseMock($data);

        $client = $this->getBitreserveClientMock();
        $client->expects($this->once())
            ->method('get')
            ->with('/me/phones')
            ->will($this->returnValue($response));

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
            'id' => '9fae84eb-712d-4b6a-9b2c-764bdde4c079',
            'firstName' => 'Foo',
            'lastName' => 'Bar',
        ));

        $response = $this->getResponseMock($data);

        $client = $this->getBitreserveClientMock();
        $client->expects($this->once())
            ->method('get')
            ->with('/me/contacts')
            ->will($this->returnValue($response));

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
                'EUR' => array('balance' => '15', 'amount' => '15', 'rate' => '1', 'currency' => 'EUR'),
                'USD' => array('balance' => '58.05', 'amount' => '75.01', 'rate' => '1.29220', 'currency' => 'EUR'),
                'XAU' => array('balance' => '0', 'amount' => '0', 'rate' => '1027.72303', 'currency' => 'EUR'),
            ),
            'total' => '58.05',
        ));

        $response = $this->getResponseMock($data);

        $client = $this->getBitreserveClientMock();
        $client->expects($this->once())
            ->method('get')
            ->with('/me')
            ->will($this->returnValue($response));

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
                'EUR' => array('balance' => '15', 'amount' => '15', 'rate' => '1', 'currency' => 'EUR'),
                'USD' => array('balance' => '58.05', 'amount' => '75.01', 'rate' => '1.29220', 'currency' => 'EUR'),
                'XAU' => array('balance' => '0', 'amount' => '0', 'rate' => '1027.72303', 'currency' => 'EUR'),
            ),
            'total' => '58.05',
        ));

        $response = $this->getResponseMock($data);

        $client = $this->getBitreserveClientMock();
        $client->expects($this->once())
            ->method('get')
            ->with('/me')
            ->will($this->returnValue($response));

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
                    'EUR' => array('balance' => '15', 'amount' => '15', 'rate' => '1', 'currency' => 'EUR'),
                    'USD' => array('balance' => '58.05', 'amount' => '75.01', 'rate' => '1.29220', 'currency' => 'EUR'),
                    'XAU' => array('balance' => '0', 'amount' => '0', 'rate' => '1027.72303', 'currency' => 'EUR'),
                ),
                'total' => '58.05',
            ),
            'settings' => array(
                'currency' => 'USD',
        ));

        $response = $this->getResponseMock($data);

        $client = $this->getBitreserveClientMock();
        $client->expects($this->once())
            ->method('get')
            ->with('/me')
            ->will($this->returnValue($response));

        $user = new User($client, $data);

        $this->assertEquals(array('amount' => '58.05', 'currency' => 'USD'), $user->getTotalBalance());
    }

    /**
     * @test
     */
    public function shouldReturnCards()
    {
        $data = array(array(
            'id' => '1',
            'label' => 'My Card',
            'currency' => 'BTC',
            'balance' => '12.03',
        ), array(
            'id' => '2',
            'label' => 'My Card 2',
            'currency' => 'EUR',
            'balance' => '499.23',
        ));

        $response = $this->getResponseMock($data);

        $client = $this->getBitreserveClientMock();
        $client->expects($this->once())
            ->method('get')
            ->with('/me/cards')
            ->will($this->returnValue($response));

        $user = new User($client, array('username' => 'foobar'));

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
        $expectedCurrency = 'BTC';

        $data = array(array(
            'id' => '1',
            'label' => 'My Card',
            'currency' => 'BTC',
            'balance' => '12.03',
        ), array(
            'id' => '2',
            'label' => 'My Card 2',
            'currency' => 'EUR',
            'balance' => '499.23',
        ));

        $response = $this->getResponseMock($data);

        $client = $this->getBitreserveClientMock();
        $client->expects($this->once())
            ->method('get')
            ->with('/me/cards')
            ->will($this->returnValue($response));

        $user = new User($client, array('username' => 'foobar'));

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
            'id' => '1',
            'label' => 'My new card',
            'currency' => 'BTC',
        );

        $response = $this->getResponseMock($data);

        $client = $this->getBitreserveClientMock();
        $client->expects($this->once())
            ->method('post')
            ->with('/me/cards')
            ->will($this->returnValue($response));

        $user = new User($client, array('username' => 'foobar'));

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
            'id' => '1',
            'address' => array(
                'bitcoin' => '145ZeN94MAtTmEgvhXEch3rRgrs7BdD2cY'
            ),
            'label' => 'My Card',
            'currency' => 'BTC',
            'balance' => '12.03',
            'available' => '12.03',
            'lastTransactionAt' => '2014-06-16T20:46:51.002Z',
            'position' => '2',
            'addresses' => array(array(
                'id' => '145ZeN94MAtTmEgvhXEch3rRgrs7BdD2cY',
                'network' => 'bitcoin',
            ))
        );

        $response = $this->getResponseMock($data);

        $client = $this->getBitreserveClientMock();
        $client->expects($this->once())
            ->method('get')
            ->with(sprintf('/me/cards/%s', $data['id']))
            ->will($this->returnValue($response));

        $user = new User($client, array('username' => 'foobar'));

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
            'id' => '1',
            'status' => 'pending',
        ), array(
            'id' => '2',
            'status' => 'completed',
        ));

        $response = $this->getResponseMock($data);

        $client = $this->getBitreserveClientMock();
        $client->expects($this->once())
            ->method('get')
            ->with('/me/transactions')
            ->will($this->returnValue($response));

        $user = new User($client, array('username' => 'foobar'));

        $pager = $user->getTransactions();

        $this->assertInstanceOf('Bitreserve\Paginator\Paginator', $pager);

        $transactions = $pager->getNext();

        $this->assertCount(count($data), $transactions);

        foreach ($transactions as $transaction) {
            $this->assertInstanceOf('Bitreserve\Model\Transaction', $transaction);
        }
    }

    public function getCurrenciesProvider()
    {
        return array(
            array('EUR'),
            array('USD'),
            array('XAU'),
        );
    }

    protected function getModelClass()
    {
        return 'Bitreserve\Model\User';
    }
}
