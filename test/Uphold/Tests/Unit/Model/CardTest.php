<?php

namespace Uphold\Tests\Unit\Model;

use Uphold\Model\Card;
use Uphold\Tests\Unit\Model\ModelTestCase;

/**
 * CardTest.
 */
class CardTest extends ModelTestCase
{
    /**
     * @test
     */
    public function shouldReturnAllFieldsFromModel()
    {
        $data = array(
            'address' => array('bitcoin' => '1GpBtJXXa1NdG94cYPGZTc3DfRY2P7EwzH'),
            'addresses' => array(array('id' => '1GpBtJXXa1NdG94cYPGZTc3DfRY2P7EwzH', 'network' => 'bitcoin')),
            'available' => $this->getFaker()->randomFloat,
            'balance' => $this->getFaker()->randomFloat,
            'currency' => $this->getFaker()->currencyCode,
            'id' => $this->getFaker()->uuid,
            'label' => $this->getFaker()->sentence(3),
            'lastTransactionAt' => '2014-09-24T18:11:53.561Z',
            'settings' => array('position' => $this->getFaker()->randomDigitNotNull, 'starred' => true),
        );

        $client = $this->getUpholdClientMock();
        $card = new Card($client, $data);

        $this->assertEquals($data, $card->toArray());
    }

    /**
     * @test
     */
    public function shouldReturnInstanceOfCard()
    {
        $data = array('id' => $this->getFaker()->randomDigitNotNull);

        $client = $this->getUpholdClientMock();

        $card = new Card($client, $data);

        $this->assertInstanceOf('Uphold\UpholdClient', $card->getClient());
        $this->assertInstanceOf('Uphold\Model\Card', $card);
    }

    /**
     * @test
     *
     * @expectedException PHPUnit_Framework_Error
     * @expectedExceptionMessage Argument 1 passed to Uphold\Model\Card::__construct() must be an
     *                           instance of Uphold\UpholdClient, string given
     */
    public function shouldThrowExceptionWhenFirstArgumentIsNotAnInstanceOfUpholdClient()
    {
        $card = new Card('foo', 'bar');
    }

    /**
     * @test
     */
    public function shouldReturnId()
    {
        $data = array('id' => $this->getFaker()->randomDigitNotNull);

        $client = $this->getUpholdClientMock();

        $card = new Card($client, $data);

        $this->assertEquals($data['id'], $card->getId());
    }

    /**
     * @test
     */
    public function shouldReturnAddress()
    {
        $data = array('address' => array('bitcoin' => '1GpBtJXXa1NdG94cYPGZTc3DfRY2P7EwzH'));

        $client = $this->getUpholdClientMock();

        $card = new Card($client, $data);

        $this->assertEquals($data['address'], $card->getAddress());
    }

    /**
     * @test
     */
    public function shouldReturnLabel()
    {
        $data = array('label' => $this->getFaker()->sentence(3));

        $client = $this->getUpholdClientMock();

        $card = new Card($client, $data);

        $this->assertEquals($data['label'], $card->getLabel());
    }

    /**
     * @test
     */
    public function shouldReturnCurrency()
    {
        $data = array('currency' => $this->getFaker()->currencyCode);

        $client = $this->getUpholdClientMock();

        $card = new Card($client, $data);

        $this->assertEquals($data['currency'], $card->getCurrency());
    }

    /**
     * @test
     */
    public function shouldReturnBalance()
    {
        $data = array('balance' => $this->getFaker()->randomFloat);

        $client = $this->getUpholdClientMock();

        $card = new Card($client, $data);

        $this->assertEquals($data['balance'], $card->getBalance());
    }

    /**
     * @test
     */
    public function shouldReturnAvailable()
    {
        $data = array('available' => $this->getFaker()->randomFloat);

        $client = $this->getUpholdClientMock();

        $card = new Card($client, $data);

        $this->assertEquals($data['available'], $card->getAvailable());
    }

    /**
     * @test
     */
    public function shouldReturnLastTransactionAt()
    {
        $data = array('lastTransactionAt' => '2014-09-24T18:11:53.561Z');

        $client = $this->getUpholdClientMock();

        $card = new Card($client, $data);

        $this->assertEquals($data['lastTransactionAt'], $card->getLastTransactionAt());
    }

    /**
     * @test
     */
    public function shouldReturnSettings()
    {
        $data = array('settings' => array('position' => $this->getFaker()->randomDigitNotNull, 'starred' => true));

        $client = $this->getUpholdClientMock();

        $card = new Card($client, $data);

        $this->assertEquals($data['settings'], $card->getSettings());
    }

    /**
     * @test
     */
    public function shouldReturnAddresses()
    {
        $cardData = array('id' => 'ade869d8-7913-4f67-bb4d-72719f0a2be0');
        $data = array(array(
            'id' => 'a97bb994-6e24-4a89-b653-e0a6d0bcf634',
            'status' => 'bitcoin',
        ), array(
            'id' => 'b97bb994-6e24-4a89-b653-e0a6d0bcf635',
            'status' => 'foobar',
        ));

        $response = $this->getResponseMock($data);
        $client = $this->getUpholdClientMock();

        $client
            ->expects($this->once())
            ->method('get')
            ->with(sprintf('/me/cards/%s/addresses', $cardData['id']))
            ->will($this->returnValue($response))
        ;

        $card = new Card($client, $cardData);

        $this->assertEquals($data, $card->getAddresses());
    }

    /**
     * @test
     */
    public function shouldReturnTransactions()
    {
        $data = array(array(
            'id' => 'a97bb994-6e24-4a89-b653-e0a6d0bcf634',
            'status' => 'pending',
        ), array(
            'id' => 'b97bb994-6e24-4a89-b653-e0a6d0bcf635',
            'status' => 'completed',
        ));

        $response = $this->getResponseMock($data);

        $cardData = array('id' => 'ade869d8-7913-4f67-bb4d-72719f0a2be0');

        $client = $this->getUpholdClientMock();

        $client
            ->expects($this->once())
            ->method('get')
            ->with(sprintf('/me/cards/%s/transactions', $cardData['id']))
            ->will($this->returnValue($response))
        ;

        $card = new Card($client, $cardData);

        $pager = $card->getTransactions();

        $this->assertInstanceOf('Uphold\Paginator\Paginator', $pager);

        $transactions = $pager->getNext();

        $this->assertCount(count($data), $transactions);

        foreach ($transactions as $transaction) {
            $this->assertInstanceOf('Uphold\Model\Transaction', $transaction);
        }
    }

    /**
     * @test
     */
    public function shouldCreateNewCryptoAddress()
    {
        $cardData = array('id' => 'ade869d8-7913-4f67-bb4d-72719f0a2be0');

        $data = array(
            'id' => 'a97bb994-6e24-4a89-b653-e0a6d0bcf634',
            'network' => 'foobar',
        );

        $postData = array('network' => 'foobar');

        $response = $this->getResponseMock($data);
        $client = $this->getUpholdClientMock();

        $client
            ->expects($this->once())
            ->method('post')
            ->with(sprintf('/me/cards/%s/addresses', $cardData['id']), $postData)
            ->will($this->returnValue($response))
        ;

        $card = new Card($client, $cardData);
        $card->createCryptoAddress('foobar');
    }

    /**
     * @test
     */
    public function shouldCreateNewTransaction()
    {
        $cardData = array('id' => 'ade869d8-7913-4f67-bb4d-72719f0a2be0');

        $postData = array(
            'destination' => $this->getFaker()->email,
            'denomination' => array(
                'amount' => $this->getFaker()->randomFloat,
                'currency' => $this->getFaker()->currencyCode,
            ),
            'message' => null,
        );

        $data = array(
            'id' => 'a97bb994-6e24-4a89-b653-e0a6d0bcf634',
            'status' => 'pending',
        );

        $response = $this->getResponseMock($data);

        $client = $this->getUpholdClientMock();

        $client
            ->expects($this->once())
            ->method('post')
            ->with(sprintf('/me/cards/%s/transactions?commit=', $cardData['id']), $postData)
            ->will($this->returnValue($response))
        ;

        $card = new Card($client, $cardData);

        $transaction = $card->createTransaction($postData['destination'], $postData['denomination']['amount'], $postData['denomination']['currency']);

        $this->assertInstanceOf('Uphold\Model\Transaction', $transaction);
        $this->assertEquals($data['id'], $transaction->getId());
    }

    /**
     * @test
     */
    public function shouldCreateNewTransactionWithCustomMessage()
    {
        $cardData = array('id' => 'ade869d8-7913-4f67-bb4d-72719f0a2be0');

        $postData = array(
            'destination' => $this->getFaker()->email,
            'denomination' => array(
                'amount' => $this->getFaker()->randomFloat,
                'currency' => $this->getFaker()->currencyCode,
            ),
            'message' => 'foobar',
        );

        $data = array(
            'id' => 'a97bb994-6e24-4a89-b653-e0a6d0bcf634',
            'status' => 'pending',
        );

        $response = $this->getResponseMock($data);

        $client = $this->getUpholdClientMock();

        $client
            ->expects($this->once())
            ->method('post')
            ->with(sprintf('/me/cards/%s/transactions?commit=', $cardData['id']), $postData)
            ->will($this->returnValue($response))
        ;

        $card = new Card($client, $cardData);

        $transaction = $card->createTransaction(
            $postData['destination'],
            $postData['denomination']['amount'],
            $postData['denomination']['currency'],
            $postData['message']
        );

        $this->assertInstanceOf('Uphold\Model\Transaction', $transaction);
        $this->assertEquals($data['id'], $transaction->getId());
    }

    /**
     * @test
     */
    public function shouldCreateNewTransactionWithCommitParameter()
    {
        $cardData = array('id' => 'ade869d8-7913-4f67-bb4d-72719f0a2be0');

        $postData = array(
            'destination' => $this->getFaker()->email,
            'denomination' => array(
                'amount' => $this->getFaker()->randomFloat,
                'currency' => $this->getFaker()->currencyCode,
            ),
            'message' => 'foobar',
        );

        $data = array(
            'id' => 'a97bb994-6e24-4a89-b653-e0a6d0bcf634',
            'status' => 'pending',
        );

        $response = $this->getResponseMock($data);

        $client = $this->getUpholdClientMock();

        $client
            ->expects($this->once())
            ->method('post')
            ->with(sprintf('/me/cards/%s/transactions?commit=1', $cardData['id']), $postData)
            ->will($this->returnValue($response))
        ;

        $card = new Card($client, $cardData);

        $transaction = $card->createTransaction(
            $postData['destination'],
            $postData['denomination']['amount'],
            $postData['denomination']['currency'],
            $postData['message'],
            true
        );

        $this->assertInstanceOf('Uphold\Model\Transaction', $transaction);
        $this->assertEquals($data['id'], $transaction->getId());
    }

    /**
     * @test
     */
    public function shouldReturnInstanceOfCardOnUpdate()
    {
        $cardData = array('id' => 'ade869d8-7913-4f67-bb4d-72719f0a2be0');
        $data = array('foo' => 'bar');

        $response = $this->getResponseMock(array_merge($cardData, $data));

        $client = $this->getUpholdClientMock();

        $client
            ->expects($this->once())
            ->method('patch')
            ->with(sprintf('/me/cards/%s', $cardData['id']), $data)
            ->will($this->returnValue($response))
        ;

        $card = new Card($client, $cardData);

        $this->assertInstanceOf('Uphold\Model\Card', $card->update($data));
    }

    /**
     * @test
     */
    public function shouldUpdateCardData()
    {
        $cardData = array(
            'id' => 'ade869d8-7913-4f67-bb4d-72719f0a2be0',
            'label' => 'qux',
        );

        $data = array(
            'currency' => 'BTC',
            'label' => 'foobar',
        );

        $response = $this->getResponseMock(array_merge($cardData, $data));

        $client = $this->getUpholdClientMock();

        $client
            ->expects($this->once())
            ->method('patch')
            ->with(sprintf('/me/cards/%s', $cardData['id']), $data)
            ->will($this->returnValue($response))
        ;

        $card = new Card($client, $cardData);
        $card = $card->update($data);

        $this->assertEquals($cardData['id'], $card->getId());
        $this->assertEquals($data['currency'], $card->getCurrency());
        $this->assertEquals($data['label'], $card->getLabel());
    }

    /**
     * Get model class.
     *
     * @return string
     */
    protected function getModelClass()
    {
        return 'Uphold\Model\Card';
    }
}
