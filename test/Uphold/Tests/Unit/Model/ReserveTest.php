<?php

namespace Uphold\Tests\Unit\Model;

use Uphold\Model\Reserve;
use Uphold\Tests\Unit\Model\ModelTestCase;

/**
 * ReserveTest.
 */
class ReserveTest extends ModelTestCase
{
    /**
     * @test
     */
    public function shouldReturnInstanceOfReserve()
    {
        $client = $this->getUpholdClientMock();

        $reserve = new Reserve($client);

        $this->assertInstanceOf('Uphold\UpholdClient', $reserve->getClient());
        $this->assertInstanceOf('Uphold\Model\Reserve', $reserve);
    }

    /**
     * @test
     */
    public function shouldReturnLedger()
    {
        $data = array('foo' => 'bar');

        $response = $this->getResponseMock($data);

        $client = $this->getUpholdClientMock();

        $client
            ->expects($this->once())
            ->method('get')
            ->with('/reserve/ledger')
            ->will($this->returnValue($response))
        ;

        $reserve = new Reserve($client);

        $pager = $reserve->getLedger();

        $this->assertInstanceOf('Uphold\Paginator\Paginator', $pager);

        $ledger = $pager->getNext();

        $this->assertEquals($data, $ledger);
    }

    /**
     * @test
     */
    public function shouldReturnStatistics()
    {
        $data = array('foo' => 'bar');

        $response = $this->getResponseMock($data);

        $client = $this->getUpholdClientMock();

        $client
            ->expects($this->once())
            ->method('get')
            ->with('/reserve/statistics')
            ->will($this->returnValue($response))
        ;

        $reserve = new Reserve($client);

        $this->assertEquals($data, $reserve->getStatistics());
    }

    /**
     * @test
     */
    public function shouldReturnTransactions()
    {
        $data = array(array(
            'id' => 'a97bb994-6e24-4a89-b653-e0a6d0bcf634',
            'status' => 'completed',
        ), array(
            'id' => '63dc7ccb-0e57-400d-8ea7-7d903753801c',
            'status' => 'pending',
        ));

        $response = $this->getResponseMock($data);

        $client = $this->getUpholdClientMock();

        $client
            ->expects($this->once())
            ->method('get')
            ->with('/reserve/transactions')
            ->will($this->returnValue($response))
        ;

        $reserve = new Reserve($client);

        $pager = $reserve->getTransactions();

        $this->assertInstanceOf('Uphold\Paginator\Paginator', $pager);

        $transactions = $pager->getNext();

        foreach ($transactions as $key => $transaction) {
            $this->assertInstanceOf('Uphold\Model\Transaction', $transaction);
            $this->assertEquals($data[$key]['id'], $transaction->getId());
            $this->assertEquals($data[$key]['status'], $transaction->getStatus());
        }
    }

    /**
     * @test
     */
    public function shouldReturnOneTransaction()
    {
        $data = array(
            'id' => 'a97bb994-6e24-4a89-b653-e0a6d0bcf634',
            'status' => 'completed',
        );

        $response = $this->getResponseMock($data);

        $client = $this->getUpholdClientMock();

        $client
            ->expects($this->once())
            ->method('get')
            ->with(sprintf('/reserve/transactions/%s', $data['id']))
            ->will($this->returnValue($response))
        ;

        $reserve = new Reserve($client);

        $transaction = $reserve->getTransactionById($data['id']);

        $this->assertInstanceOf('Uphold\Model\Transaction', $transaction);
        $this->assertEquals($data['id'], $transaction->getId());
        $this->assertEquals($data['status'], $transaction->getStatus());
    }

    /**
     * Get model class.
     *
     * @return string
     */
    protected function getModelClass()
    {
        return 'Uphold\Model\Reserve';
    }
}
