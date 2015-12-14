<?php

namespace Uphold\Tests\Functional;

use Uphold\UpholdClient;

/**
 * UpholdClientTest.
 *
 * @group functional
 */
class UpholdClientTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnRates()
    {
        $rates = $this->client->getRates();

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
        $rates = $this->client->getRatesByCurrency($currency);

        foreach ($rates as $rate) {
            $this->assertObjectHasAttribute('ask', $rate);
            $this->assertObjectHasAttribute('bid', $rate);
            $this->assertObjectHasAttribute('currency', $rate);
            $this->assertObjectHasAttribute('pair', $rate);
        }
    }

    /**
     * @test
     * @expectedException Uphold\Exception\NotFoundException
     */
    public function shouldThrowExceptionWhenCurrencyIsNotValid()
    {
        $currency = 'FOO';

        $rates = $this->client->getRatesByCurrency($currency);
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

    /**
     * Get currencies provider.
     *
     * @return array
     */
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
