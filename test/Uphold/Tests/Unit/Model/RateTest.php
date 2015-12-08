<?php

namespace Uphold\Tests\Unit\Model;

use Uphold\Model\Rate;
use Uphold\Tests\Unit\Model\ModelTestCase;

/**
 * RateTest.
 */
class RateTest extends ModelTestCase
{
    /**
     * @test
     */
    public function shouldReturnAllFieldsFromModel()
    {
        $data = array(
            'ask' => '1',
            'bid' => '2',
            'currency' => 'BTC',
            'pair' => 'USDBTC',
        );

        $client = $this->getUpholdClientMock();
        $rate = new Rate($client, $data);

        $this->assertEquals($data, $rate->toArray());
    }

    /**
     * @test
     */
    public function shouldReturnInstanceOfRate()
    {
        $data = array('ask' => '1');

        $client = $this->getUpholdClientMock();

        $rate = new Rate($client, $data);

        $this->assertInstanceOf('Uphold\UpholdClient', $rate->getClient());
        $this->assertInstanceOf('Uphold\Model\Rate', $rate);
    }

    /**
     * @test
     */
    public function shouldReturnAsk()
    {
        $data = array('ask' => '1');

        $client = $this->getUpholdClientMock();

        $rate = new Rate($client, $data);

        $this->assertEquals($data['ask'], $rate->getAsk());
    }

    /**
     * @test
     */
    public function shouldReturnBid()
    {
        $data = array('bid' => '1');

        $client = $this->getUpholdClientMock();

        $rate = new Rate($client, $data);

        $this->assertEquals($data['bid'], $rate->getBid());
    }

    /**
     * @test
     */
    public function shouldReturnCurrency()
    {
        $data = array('currency' => 'BTC');

        $client = $this->getUpholdClientMock();

        $rate = new Rate($client, $data);

        $this->assertEquals($data['currency'], $rate->getCurrency());
    }

    /**
     * @test
     */
    public function shouldReturnPair()
    {
        $data = array('pair' => '1');

        $client = $this->getUpholdClientMock();

        $rate = new Rate($client, $data);

        $this->assertEquals($data['pair'], $rate->getPair());
    }

    /**
     * Get model class.
     *
     * @return string
     */
    protected function getModelClass()
    {
        return 'Uphold\Model\Rate';
    }
}
