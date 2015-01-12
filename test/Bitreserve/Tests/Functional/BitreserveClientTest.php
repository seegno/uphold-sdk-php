<?php

namespace Bitreserve\Tests\Functional;

use Bitreserve\BitreserveClient;

/**
 * @group functional
 */
class BitreserveClientTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnTickers()
    {
        $tickers = $this->client->getTicker();

        foreach ($tickers as $ticker) {
            $this->assertObjectHasAttribute('ask', $ticker);
            $this->assertObjectHasAttribute('bid', $ticker);
            $this->assertObjectHasAttribute('currency', $ticker);
            $this->assertObjectHasAttribute('pair', $ticker);
        }
    }

    /**
     * @test
     * @dataProvider getCurrenciesProvider
     */
    public function shouldReturnTickersForACurrency($currency)
    {
        $tickers = $this->client->getTickerByCurrency($currency);

        foreach ($tickers as $ticker) {
            $this->assertObjectHasAttribute('ask', $ticker);
            $this->assertObjectHasAttribute('bid', $ticker);
            $this->assertObjectHasAttribute('currency', $ticker);
            $this->assertObjectHasAttribute('pair', $ticker);
        }
    }

    /**
     * @test
     */
    public function shouldReturnTransactions()
    {
        $transactions = $this->client->getTransactions();

        // Test only one object because method returns a lot of transactions.
        $this->assertInstanceOf('Bitreserve\Model\Transaction', $transactions[0]);
    }

    /**
     * @test
     */
    public function shouldReturnOneTransactions()
    {
        $exampleTransactionId = '66fc2a0d-a933-45f0-ba27-8cf12870fcce';

        $transaction = $this->client->getTransactionById($exampleTransactionId);

        $this->assertInstanceOf('Bitreserve\Model\Transaction', $transaction);
        $this->assertEquals($exampleTransactionId, $transaction->getId());
    }

    /**
     * @test
     * @expectedException \Bitreserve\Exception\BadRequestException
     */
    public function shouldThrowExceptionWhenCurrencyIsNotValid()
    {
        $currency = 'FOO';

        $tickers = $this->client->getTickerByCurrency($currency);
    }

    /**
     * @test
     */
    public function shouldGetAllCurrencies()
    {
        $currencies = $this->client->getCurrencies();

        $this->assertInternalType('array', $currencies);
        $this->assertGreaterThan(0, count($currencies));
    }

    public function getCurrenciesProvider()
    {
        return array(
            array('BTC'),
            array('USD'),
            array('CNY'),
            array('EUR'),
            array('GBP'),
            array('JPY'),
            array('XAU'),
        );
    }
}
