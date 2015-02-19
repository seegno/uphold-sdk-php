<?php

namespace Bitreserve\Tests\Functional;

use Bitreserve\BitreserveClient;

/**
 * BitreserveClientTest.
 *
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
     * @expectedException Bitreserve\Exception\NotFoundException
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
