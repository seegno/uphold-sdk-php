<?php

namespace Bitreserve\Tests\Model;

use Bitreserve\Model\Transaction;

class TransactionTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnInstanceOfTransaction()
    {
        $data = array('id' => '1');

        $client = $this->getBitreserveClientMock();

        $transaction = new Transaction($client, $data);

        $this->assertInstanceOf('Bitreserve\BitreserveClient', $transaction->getClient());
        $this->assertInstanceOf('Bitreserve\Model\Transaction', $transaction);
    }

    /**
     * @test
     */
    public function shouldReturnId()
    {
        $data = array('id' => 'a97bb994-6e24-4a89-b653-e0a6d0bcf634');

        $client = $this->getBitreserveClientMock();

        $transaction = new Transaction($client, $data);

        $this->assertEquals($data['id'], $transaction->getId());
    }

    /**
     * @test
     */
    public function shouldReturnMessage()
    {
        $data = array('message' => '1');

        $client = $this->getBitreserveClientMock();

        $transaction = new Transaction($client, $data);

        $this->assertEquals($data['message'], $transaction->getMessage());
    }

    /**
     * @test
     */
    public function shouldReturnStatus()
    {
        $data = array('status' => 'pending');

        $client = $this->getBitreserveClientMock();

        $transaction = new Transaction($client, $data);

        $this->assertEquals($data['status'], $transaction->getStatus());
    }

    /**
     * @test
     */
    public function shouldReturnRefundedById()
    {
        $data = array('refundedById' => '1');

        $client = $this->getBitreserveClientMock();

        $transaction = new Transaction($client, $data);

        $this->assertEquals($data['refundedById'], $transaction->getRefundedById());
    }

    /**
     * @test
     */
    public function shouldReturnCreatedAt()
    {
        $data = array('createdAt' => '2014-08-27T00:01:11.616Z');

        $client = $this->getBitreserveClientMock();

        $transaction = new Transaction($client, $data);

        $this->assertEquals($data['createdAt'], $transaction->getCreatedAt());
    }

    /**
     * @test
     */
    public function shouldReturnDenomination()
    {
        $data = array('denomination' => array(
            'rate' => '1.00',
            'amount' => '1.00',
            'currency' => 'USD',
        ));

        $client = $this->getBitreserveClientMock();

        $transaction = new Transaction($client, $data);

        $this->assertEquals($data['denomination'], $transaction->getDenomination());
    }

    /**
     * @test
     */
    public function shouldReturnOrigin()
    {
        $data = array('origin' => array('foo' => 'bar'));

        $client = $this->getBitreserveClientMock();

        $transaction = new Transaction($client, $data);

        $this->assertEquals($data['origin'], $transaction->getOrigin());
    }

    /**
     * @test
     */
    public function shouldReturnDestination()
    {
        $data = array('destination' => array('foo' => 'bar'));

        $client = $this->getBitreserveClientMock();

        $transaction = new Transaction($client, $data);

        $this->assertEquals($data['destination'], $transaction->getDestination());
    }

    /**
     * @test
     */
    public function shouldReturnParams()
    {
        $data = array('params' => array('foo' => 'bar'));

        $client = $this->getBitreserveClientMock();

        $transaction = new Transaction($client, $data);

        $this->assertEquals($data['params'], $transaction->getParams());
    }

    /**
     * @test
     */
    public function shouldCommit()
    {
        $data = array(
            'id' => 'a97bb994-6e24-4a89-b653-e0a6d0bcf634',
            'cardId' => '91380a1f-c6f1-4d81-a204-8b40538c1f0d',
            'signature' => '1d326154e7a68c64a650af9d3233d77b8a385ce0',
            'status' => 'pending',
        );

        $client = $this->getBitreserveClientMock();
        $client->expects($this->once())
            ->method('post')
            ->with(sprintf('/me/cards/%s/transactions/%s/commit', $data['cardId'], $data['id']))
            ->will($this->returnValue($data));

        $transaction = new Transaction($client, $data);
        $transaction->setCardId($data['cardId']);

        $transaction->commit();

        $this->assertEquals($data['id'], $transaction->getId());
    }

    /**
     * @test
     * @expectedException \Bitreserve\Exception\ErrorException
     */
    public function shouldCommitThownAnErrorWhenStatusIsNotPending()
    {
        $data = array(
            'id' => 'a97bb994-6e24-4a89-b653-e0a6d0bcf634',
            'cardId' => '91380a1f-c6f1-4d81-a204-8b40538c1f0d',
            'signature' => '1d326154e7a68c64a650af9d3233d77b8a385ce0',
            'status' => 'completed',
        );

        $client = $this->getBitreserveClientMock();

        $transaction = new Transaction($client, $data);
        $transaction->setCardId($data['cardId']);

        $transaction->commit();
    }

    /**
     * @test
     * @expectedException \Bitreserve\Exception\ErrorException
     */
    public function shouldCommitThownAnErrorWhenCardIdIsNotDefined()
    {
        $data = array(
            'id' => 'a97bb994-6e24-4a89-b653-e0a6d0bcf634',
        );

        $client = $this->getBitreserveClientMock();

        $transaction = new Transaction($client, $data);
        $transaction->commit();
    }

    /**
     * @test
     */
    public function shouldCancel()
    {
        $data = array(
            'id' => 'a97bb994-6e24-4a89-b653-e0a6d0bcf634',
            'cardId' => '91380a1f-c6f1-4d81-a204-8b40538c1f0d',
            'signature' => '1d326154e7a68c64a650af9d3233d77b8a385ce0',
            'status' => 'waiting',
        );

        $client = $this->getBitreserveClientMock();
        $client->expects($this->once())
            ->method('post')
            ->with(sprintf('/me/cards/%s/transactions/%s/cancel', $data['cardId'], $data['id']))
            ->will($this->returnValue($data));

        $transaction = new Transaction($client, $data);
        $transaction->setCardId($data['cardId']);

        $transaction->cancel();

        $this->assertEquals($data['id'], $transaction->getId());
    }

    /**
     * @test
     * @expectedException \Bitreserve\Exception\ErrorException
     */
    public function shouldCancelThownAnErrorWhenStatusIsNotPending()
    {
        $data = array(
            'id' => 'a97bb994-6e24-4a89-b653-e0a6d0bcf634',
            'cardId' => '91380a1f-c6f1-4d81-a204-8b40538c1f0d',
            'signature' => '1d326154e7a68c64a650af9d3233d77b8a385ce0',
            'status' => 'completed',
        );

        $client = $this->getBitreserveClientMock();

        $transaction = new Transaction($client, $data);
        $transaction->setCardId($data['cardId']);

        $transaction->cancel();
    }

    /**
     * @test
     * @expectedException \Bitreserve\Exception\ErrorException
     */
    public function shouldCancelThownAnErrorWhenCardIdIsNotDefined()
    {
        $data = array(
            'id' => 'a97bb994-6e24-4a89-b653-e0a6d0bcf634',
        );

        $client = $this->getBitreserveClientMock();

        $transaction = new Transaction($client, $data);
        $transaction->cancel();
    }

    protected function getModelClass()
    {
        return 'Bitreserve\Model\Transaction';
    }
}
