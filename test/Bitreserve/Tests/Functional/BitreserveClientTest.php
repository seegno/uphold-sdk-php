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
    public function shouldReturnRates()
    {
        $rates = $this->client->getTicker();

        foreach ($rates as $rate) {
            $this->assertObjectHasAttribute('ask', $rate);
            $this->assertObjectHasAttribute('bid', $rate);
            $this->assertObjectHasAttribute('currency', $rate);
            $this->assertObjectHasAttribute('pair', $rate);
        }
    }

    /**
     * @test
     * @dataProvider getCurrenciesProvider
     */
    public function shouldReturnRatesForACurrency($currency)
    {
        $rates = $this->client->getTickerByCurrency($currency);

        foreach ($rates as $rate) {
            $this->assertObjectHasAttribute('ask', $rate);
            $this->assertObjectHasAttribute('bid', $rate);
            $this->assertObjectHasAttribute('currency', $rate);
            $this->assertObjectHasAttribute('pair', $rate);
        }
    }

    /**
     * @test
     * @expectedException Bitreserve\Exception\NotFoundException
     */
    public function shouldThrowExceptionWhenCurrencyIsNotValid()
    {
        $currency = 'FOO';

        $rates = $this->client->getTickerByCurrency($currency);
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
