<?php

namespace Uphold\Tests\Functional;

/**
 * ReserveTest.
 *
 * @group functional
 */
class ReserveTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnTransactions()
    {
        $pager = $this->client->getReserve()->getTransactions();

        $transactions = $pager->getNext();

        foreach ($transactions as $transaction) {
            $this->assertInstanceOf('Uphold\Model\Transaction', $transaction);
        }
    }

    /**
     * @test
     */
    public function shouldReturnOneTransactions()
    {
        $exampleTransactionId = 'af3ef9a7-9262-4022-b376-7b4d928f7206';

        $transaction = $this->client->getReserve()->getTransactionById($exampleTransactionId);

        $this->assertInstanceOf('Uphold\Model\Transaction', $transaction);
        $this->assertEquals($exampleTransactionId, $transaction->getId());
    }
}
