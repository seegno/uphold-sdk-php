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
        $exampleTransactionId = '66fc2a0d-a933-45f0-ba27-8cf12870fcce';

        $transaction = $this->client->getReserve()->getTransactionById($exampleTransactionId);

        $this->assertInstanceOf('Uphold\Model\Transaction', $transaction);
        $this->assertEquals($exampleTransactionId, $transaction->getId());
    }
}
