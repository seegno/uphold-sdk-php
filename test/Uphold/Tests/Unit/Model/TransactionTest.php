<?php

namespace Uphold\Tests\Unit\Model;

use Uphold\Model\Transaction;
use Uphold\Tests\Unit\Model\ModelTestCase;

/**
 * TransactionTest.
 */
class TransactionTest extends ModelTestCase
{
    /**
     * @test
     */
    public function shouldReturnAllFieldsFromModel()
    {
        $data = array(
            'createdAt' => '2014-08-27T00:01:11.616Z',
            'denomination' => 'foobar',
            'destination' => 'qux',
            'id' => $this->getFaker()->uuid,
            'message' => $this->getFaker()->sentence(3),
            'origin' => $this->getFaker()->uuid,
            'params' => array('foo' => 'bar'),
            'refundedById' => $this->getFaker()->uuid,
            'status' => 'pending',
            'type' => 'waldo',
        );

        $client = $this->getUpholdClientMock();
        $transaction = new Transaction($client, $data);

        $this->assertEquals($data, $transaction->toArray());
    }

    /**
     * @test
     */
    public function shouldReturnInstanceOfTransaction()
    {
        $data = array('id' => $this->getFaker()->randomDigitNotNull);

        $client = $this->getUpholdClientMock();

        $transaction = new Transaction($client, $data);

        $this->assertInstanceOf('Uphold\UpholdClient', $transaction->getClient());
        $this->assertInstanceOf('Uphold\Model\Transaction', $transaction);
    }

    /**
     * @test
     */
    public function shouldReturnId()
    {
        $data = array('id' => $this->getFaker()->uuid);

        $client = $this->getUpholdClientMock();

        $transaction = new Transaction($client, $data);

        $this->assertEquals($data['id'], $transaction->getId());
    }

    /**
     * @test
     */
    public function shouldReturnMessage()
    {
        $data = array('message' => '1');

        $client = $this->getUpholdClientMock();

        $transaction = new Transaction($client, $data);

        $this->assertEquals($data['message'], $transaction->getMessage());
    }

    /**
     * @test
     */
    public function shouldReturnStatus()
    {
        $data = array('status' => 'pending');

        $client = $this->getUpholdClientMock();

        $transaction = new Transaction($client, $data);

        $this->assertEquals($data['status'], $transaction->getStatus());
    }

    /**
     * @test
     */
    public function shouldReturnRefundedById()
    {
        $data = array('refundedById' => '1');

        $client = $this->getUpholdClientMock();

        $transaction = new Transaction($client, $data);

        $this->assertEquals($data['refundedById'], $transaction->getRefundedById());
    }

    /**
     * @test
     */
    public function shouldReturnCreatedAt()
    {
        $data = array('createdAt' => '2014-08-27T00:01:11.616Z');

        $client = $this->getUpholdClientMock();

        $transaction = new Transaction($client, $data);

        $this->assertEquals($data['createdAt'], $transaction->getCreatedAt());
    }

    /**
     * @test
     */
    public function shouldReturnDenomination()
    {
        $data = array('denomination' => array(
            'rate' => $this->getFaker()->randomFloat(2, 1, 2),
            'amount' => $this->getFaker()->randomFloat,
            'currency' => $this->getFaker()->currencyCode,
        ));

        $client = $this->getUpholdClientMock();

        $transaction = new Transaction($client, $data);

        $this->assertEquals($data['denomination'], $transaction->getDenomination());
    }

    /**
     * @test
     */
    public function shouldReturnOrigin()
    {
        $data = array('origin' => array('foo' => 'bar'));

        $client = $this->getUpholdClientMock();

        $transaction = new Transaction($client, $data);

        $this->assertEquals($data['origin'], $transaction->getOrigin());
    }

    /**
     * @test
     */
    public function shouldReturnDestination()
    {
        $data = array('destination' => array('foo' => 'bar'));

        $client = $this->getUpholdClientMock();

        $transaction = new Transaction($client, $data);

        $this->assertEquals($data['destination'], $transaction->getDestination());
    }

    /**
     * @test
     */
    public function shouldReturnParams()
    {
        $data = array('params' => array('foo' => 'bar'));

        $client = $this->getUpholdClientMock();

        $transaction = new Transaction($client, $data);

        $this->assertEquals($data['params'], $transaction->getParams());
    }

    /**
     * @test
     */
    public function shouldReturnType()
    {
        $data = array('type' => 'foobar');

        $client = $this->getUpholdClientMock();

        $transaction = new Transaction($client, $data);

        $this->assertEquals($data['type'], $transaction->getType());
    }

    /**
     * @test
     */
    public function shouldCommit()
    {
        $data = array(
            'id' => $this->getFaker()->uuid,
            'origin' => array(
                'CardId' => $this->getFaker()->uuid,
            ),
            'signature' => '1d326154e7a68c64a650af9d3233d77b8a385ce0',
            'status' => 'pending',
        );

        $response = $this->getResponseMock($data);

        $client = $this->getUpholdClientMock();

        $client
            ->expects($this->once())
            ->method('post')
            ->with(sprintf('/me/cards/%s/transactions/%s/commit', $data['origin']['CardId'], $data['id']))
            ->will($this->returnValue($response))
        ;

        $transaction = new Transaction($client, $data);
        $transaction->commit();

        $this->assertEquals($data['id'], $transaction->getId());
    }

    /**
     * @test
     * @expectedException Uphold\Exception\LogicException
     */
    public function shouldThrowAnErrorOnCommitWhenStatusIsNotPending()
    {
        $data = array(
            'id' => $this->getFaker()->uuid,
            'origin' => array(
                'CardId' => $this->getFaker()->uuid,
            ),
            'signature' => '1d326154e7a68c64a650af9d3233d77b8a385ce0',
            'status' => 'completed',
        );

        $client = $this->getUpholdClientMock();

        $transaction = new Transaction($client, $data);
        $transaction->commit();
    }

    /**
     * @test
     * @expectedException Uphold\Exception\LogicException
     */
    public function shouldThrowAnErrorOnCommitWhenCardIdIsNotDefined()
    {
        $data = array(
            'id' => $this->getFaker()->uuid,
        );

        $client = $this->getUpholdClientMock();

        $transaction = new Transaction($client, $data);
        $transaction->commit();
    }

    /**
     * @test
     */
    public function shouldCancel()
    {
        $data = array(
            'id' => $this->getFaker()->uuid,
            'origin' => array(
                'CardId' => $this->getFaker()->uuid,
            ),
            'signature' => '1d326154e7a68c64a650af9d3233d77b8a385ce0',
            'status' => 'waiting',
        );

        $response = $this->getResponseMock($data);

        $client = $this->getUpholdClientMock();

        $client
            ->expects($this->once())
            ->method('post')
            ->with(sprintf('/me/cards/%s/transactions/%s/cancel', $data['origin']['CardId'], $data['id']))
            ->will($this->returnValue($response))
        ;

        $transaction = new Transaction($client, $data);
        $transaction->cancel();

        $this->assertEquals($data['id'], $transaction->getId());
    }

    /**
     * @test
     * @expectedException Uphold\Exception\LogicException
     * @expectedExceptionMessage This transaction cannot be cancelled, because the current status is completed
     */
    public function shouldThrowAnErrorOnCancelWhenStatusIsNotWaiting()
    {
        $data = array(
            'id' => $this->getFaker()->uuid,
            'origin' => array(
                'CardId' => '91380a1f-c6f1-4d81-a204-8b40538c1f0d',
            ),
            'signature' => '1d326154e7a68c64a650af9d3233d77b8a385ce0',
            'status' => 'completed',
        );

        $client = $this->getUpholdClientMock();

        $transaction = new Transaction($client, $data);
        $transaction->cancel();
    }

    /**
     * @test
     * @expectedException Uphold\Exception\LogicException
     * @expectedExceptionMessage Unable to cancel uncommited transaction
     */
    public function shouldThrowAnErrorOnCancelWhenStatusIsPending()
    {
        $data = array(
            'id' => $this->getFaker()->uuid,
            'origin' => array(
                'CardId' => '91380a1f-c6f1-4d81-a204-8b40538c1f0d',
            ),
            'signature' => '1d326154e7a68c64a650af9d3233d77b8a385ce0',
            'status' => 'pending',
        );

        $client = $this->getUpholdClientMock();

        $transaction = new Transaction($client, $data);
        $transaction->cancel();
    }

    /**
     * @test
     * @expectedException Uphold\Exception\LogicException
     * @expectedExceptionMessage Origin `CardId` is missing from this transaction
     */
    public function shouldThrowAnErrorOnCancelWhenCardIdIsNotDefined()
    {
        $data = array(
            'id' => $this->getFaker()->uuid,
        );

        $client = $this->getUpholdClientMock();

        $transaction = new Transaction($client, $data);
        $transaction->cancel();
    }

    /**
     * @test
     */
    public function shouldResend()
    {
        $data = array(
            'id' => $this->getFaker()->uuid,
            'origin' => array(
                'CardId' => $this->getFaker()->uuid,
            ),
            'signature' => '1d326154e7a68c64a650af9d3233d77b8a385ce0',
            'status' => 'waiting',
        );

        $response = $this->getResponseMock($data);

        $client = $this->getUpholdClientMock();

        $client
            ->expects($this->once())
            ->method('post')
            ->with(sprintf('/me/cards/%s/transactions/%s/resend', $data['origin']['CardId'], $data['id']))
            ->will($this->returnValue($response))
        ;

        $transaction = new Transaction($client, $data);
        $transaction->resend();

        $this->assertEquals($data['id'], $transaction->getId());
    }

    /**
     * @test
     * @expectedException Uphold\Exception\LogicException
     * @expectedExceptionMessage Origin `CardId` is missing from this transaction
     */
    public function shouldThrowAnErrorOnResendWhenCardIdIsNotDefined()
    {
        $data = array(
            'id' => 'a97bb994-6e24-4a89-b653-e0a6d0bcf634',
        );

        $client = $this->getUpholdClientMock();

        $transaction = new Transaction($client, $data);
        $transaction->resend();
    }

    /**
     * @test
     * @expectedException Uphold\Exception\LogicException
     * @expectedExceptionMessage This transaction cannot be resent, because the current status is pending
     */
    public function shouldThrowAnErrorOnResendWhenStatusIsNotWaiting()
    {
        $data = array(
            'id' => 'a97bb994-6e24-4a89-b653-e0a6d0bcf634',
            'origin' => array(
                'CardId' => '91380a1f-c6f1-4d81-a204-8b40538c1f0d',
            ),
            'signature' => '1d326154e7a68c64a650af9d3233d77b8a385ce0',
            'status' => 'pending',
        );

        $client = $this->getUpholdClientMock();

        $transaction = new Transaction($client, $data);
        $transaction->resend();
    }

    /**
     * Get model class.
     *
     * @return string
     */
    protected function getModelClass()
    {
        return 'Uphold\Model\Transaction';
    }
}
